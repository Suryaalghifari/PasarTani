const Logistics = require('../models/Logistics');
const Order = require('../models/Order');
const PickupPoint = require('../models/PickupPoint');
const User = require('../models/User');

// Admin/bagian logistik: Buat data pengiriman untuk order tertentu
exports.createLogistics = async (req, res) => {
  try {
    const {
      id_order,
      id_petani,
      id_titik_ambil,
      jadwal_pengambilan,
      jadwal_pengiriman,
      kurir,
      catatan
    } = req.body;

    // Validasi
    const order = await Order.findById(id_order);
    if (!order) return res.status(404).json({ message: 'Order tidak ditemukan' });
    const pickupPoint = await PickupPoint.findById(id_titik_ambil);
    if (!pickupPoint) return res.status(404).json({ message: 'Titik ambil tidak ditemukan' });
    const petani = await User.findById(id_petani);
    if (!petani) return res.status(404).json({ message: 'Petani tidak ditemukan' });

    const logistics = new Logistics({
      id_order,
      id_petani,
      id_titik_ambil,
      jadwal_pengambilan,
      jadwal_pengiriman,
      kurir,
      catatan,
      status_pengiriman: 'menunggu'
    });

    await logistics.save();
    res.status(201).json({ message: 'Data logistik berhasil dibuat', logistics });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get semua data logistik (admin/logistik)
exports.getAllLogistics = async (req, res) => {
  try {
    const logisticsList = await Logistics.find()
      .populate('id_order')
      .populate('id_petani', 'nama')
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });
    res.json(logisticsList);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get data logistik untuk satu order
exports.getLogisticsByOrder = async (req, res) => {
  try {
    const logistics = await Logistics.findOne({ id_order: req.params.orderId })
      .populate('id_order')
      .populate('id_petani', 'nama')
      .populate('id_titik_ambil', 'nama alamat');
    if (!logistics) return res.status(404).json({ message: 'Data logistik tidak ditemukan' });
    res.json(logistics);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Update status pengiriman (admin/petugas logistik)
exports.updateLogisticsStatus = async (req, res) => {
  try {
    const { status_pengiriman, catatan } = req.body;
    const logistics = await Logistics.findByIdAndUpdate(
      req.params.id,
      { $set: { status_pengiriman, catatan } },
      { new: true, runValidators: true }
    );
    if (!logistics) return res.status(404).json({ message: 'Data logistik tidak ditemukan' });
    res.json({ message: 'Status pengiriman diupdate', logistics });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get logistics untuk petani tertentu (dashboard petani)
exports.getLogisticsForPetani = async (req, res) => {
  try {
    const logisticsList = await Logistics.find({ id_petani: req.user.userId })
      .populate('id_order')
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });
    res.json(logisticsList);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

