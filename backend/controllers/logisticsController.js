const Logistics = require("../models/Logistics");
const Order = require("../models/Order");
const PickupPoint = require("../models/PickupPoint");
const User = require("../models/User");

// ===============================
// 1. Admin/bagian logistik: Buat data pengiriman untuk order tertentu
exports.createLogistics = async (req, res) => {
  try {
    const {
      id_order,
      id_petani,
      id_titik_ambil,
      jadwal_pengambilan,
      jadwal_pengiriman,
      kurir,
      catatan,
    } = req.body;

    // Validasi
    const order = await Order.findById(id_order);
    if (!order)
      return res.status(404).json({ message: "Order tidak ditemukan" });
    const pickupPoint = await PickupPoint.findById(id_titik_ambil);
    if (!pickupPoint)
      return res.status(404).json({ message: "Titik ambil tidak ditemukan" });
    const petani = await User.findById(id_petani);
    if (!petani)
      return res.status(404).json({ message: "Petani tidak ditemukan" });

    // Buat data logistik
    const logistics = new Logistics({
      id_order,
      id_petani,
      id_titik_ambil,
      jadwal_pengambilan,
      jadwal_pengiriman,
      kurir,
      catatan,
      status_pengiriman: "menunggu",
    });

    await logistics.save();

    // ===== Tambahan: update status order ke "dikirim"
    await Order.findByIdAndUpdate(id_order, { status_pesanan: "dikirim" });

    res
      .status(201)
      .json({ message: "Data logistik berhasil dibuat", logistics });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// ===============================
// 2. Get semua data logistik (admin/logistik)
exports.getAllLogistics = async (req, res) => {
  try {
    const logisticsList = await Logistics.find()
      .populate("id_order")
      .populate("id_petani", "nama")
      .populate("id_titik_ambil", "nama alamat")
      .sort({ createdAt: -1 });
    res.json(logisticsList);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// ===============================
// 3. Get data logistik untuk satu order
exports.getLogisticsByOrder = async (req, res) => {
  try {
    const logistics = await Logistics.findOne({ id_order: req.params.orderId })
      .populate({
        path: "id_order",
        populate: { path: "id_konsumen", select: "nama email" }, // <--- Penting!
      })
      .populate("id_petani", "nama")
      .populate("id_titik_ambil", "nama alamat");
    if (!logistics)
      return res.status(404).json({ message: "Data logistik tidak ditemukan" });
    res.json(logistics);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// ===============================
// 4. Update status pengiriman (admin/petugas logistik)
exports.updateLogisticsStatus = async (req, res) => {
  try {
    const { status_pengiriman, catatan } = req.body;
    const logistics = await Logistics.findByIdAndUpdate(
      req.params.id,
      { $set: { status_pengiriman, catatan } },
      { new: true, runValidators: true }
    );
    if (!logistics)
      return res.status(404).json({ message: "Data logistik tidak ditemukan" });

    // ===== Tambahan: jika status_pengiriman "selesai", update order ke "selesai"
    if (status_pengiriman === "selesai" && logistics.id_order) {
      await Order.findByIdAndUpdate(logistics.id_order, {
        status_pesanan: "selesai",
      });
    }

    res.json({ message: "Status pengiriman diupdate", logistics });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// ===============================
// 5. Get logistics untuk petani tertentu (dashboard petani)
exports.getLogisticsForPetani = async (req, res) => {
  try {
    const logisticsList = await Logistics.find({ id_petani: req.user.userId })
      .populate("id_order")
      .populate("id_titik_ambil", "nama alamat")
      .sort({ createdAt: -1 });
    res.json(logisticsList);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
