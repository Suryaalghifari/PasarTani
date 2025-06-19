const Order = require("../models/Order");
const Product = require("../models/Product");
const PickupPoint = require("../models/PickupPoint");

// Buat order baru (Checkout)
exports.createOrder = async (req, res) => {
  try {
    const {
      items,
      total_harga,
      alamat_pengiriman,
      no_hp,
      id_titik_ambil,
      metode_pembayaran,
      catatan,
    } = req.body;

    if (!items || !Array.isArray(items) || items.length === 0) {
      return res
        .status(400)
        .json({ message: "Order harus berisi minimal 1 produk" });
    }
    const pickupPoint = await PickupPoint.findById(id_titik_ambil);
    if (!pickupPoint || pickupPoint.status !== "aktif") {
      return res
        .status(400)
        .json({ message: "Titik pengambilan tidak valid/aktif" });
    }

    for (let item of items) {
      const produk = await Product.findById(item.produk);
      if (!produk)
        return res
          .status(400)
          .json({ message: `Produk ${item.nama_produk} tidak ditemukan` });
      if (produk.stok < item.jumlah)
        return res
          .status(400)
          .json({ message: `Stok produk ${item.nama_produk} tidak mencukupi` });
    }

    const order = new Order({
      id_konsumen: req.user.userId,
      items,
      total_harga,
      alamat_pengiriman,
      no_hp,
      id_titik_ambil,
      metode_pembayaran,
      catatan,
    });
    await order.save();

    for (let item of items) {
      await Product.findByIdAndUpdate(item.produk, {
        $inc: { stok: -item.jumlah },
      });
    }

    res.status(201).json({ message: "Order berhasil dibuat", order });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// 1. Get semua order (admin) / Get order by filter
exports.getAllOrders = async (req, res) => {
  try {
    // Query filter opsional (misal untuk status_pesanan)
    const filter = {};
    if (req.query.status_pesanan)
      filter.status_pesanan = req.query.status_pesanan;

    const orders = await Order.find(filter)
      .populate("id_konsumen", "nama email")
      .populate("id_titik_ambil", "nama alamat")
      .populate({
        path: "items.produk",
        populate: { path: "id_petani", select: "nama" }, // Nested populate id_petani
      })
      .sort({ createdAt: -1 });

    res.json(orders);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// 2. Get order milik user login (konsumen)
exports.getMyOrders = async (req, res) => {
  try {
    const orders = await Order.find({ id_konsumen: req.user.userId })
      .populate("id_titik_ambil", "nama alamat")
      .populate({
        path: "items.produk",
        populate: { path: "id_petani", select: "nama" },
      })
      .sort({ createdAt: -1 });
    res.json(orders);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// 3. Get order untuk produk petani (petani dashboard)
exports.getOrdersForPetani = async (req, res) => {
  try {
    const userId = String(req.user.userId);

    // Selalu populate produk & id_petani!
    const orders = await Order.find()
      .populate({
        path: "items.produk",
        populate: { path: "id_petani", select: "nama" },
      })
      .populate("id_konsumen", "nama email")
      .populate("id_titik_ambil", "nama alamat")
      .sort({ createdAt: -1 });

    // Filter order yang punya produk milik petani login
    const filteredOrders = orders.filter((order) =>
      order.items.some(
        (item) =>
          item.produk &&
          item.produk.id_petani &&
          String(item.produk.id_petani._id) === userId
      )
    );
    res.json(filteredOrders);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// 4. Update status pembayaran/order (admin/petugas/petani, sesuai role & fitur)
exports.updateOrderStatus = async (req, res) => {
  try {
    const { status_pesanan, status_pembayaran } = req.body;
    const updateData = {};
    if (status_pesanan) updateData.status_pesanan = status_pesanan;
    if (status_pembayaran) updateData.status_pembayaran = status_pembayaran;

    const order = await Order.findByIdAndUpdate(
      req.params.id,
      { $set: updateData },
      { new: true, runValidators: true }
    );
    if (!order)
      return res.status(404).json({ message: "Order tidak ditemukan" });

    res.json({ message: "Status order berhasil diupdate", order });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// 5. Petani update status pesanan (SIAP-DIAMBIL)
exports.petaniUpdateOrderStatus = async (req, res) => {
  try {
    const { status_pesanan, catatan } = req.body;
    const order = await Order.findById(req.params.id).populate({
      path: "items.produk",
      populate: { path: "id_petani", select: "nama" },
    });
    if (!order)
      return res.status(404).json({ message: "Order tidak ditemukan" });

    // Pastikan petani adalah pemilik produk
    const isPetani = order.items.some(
      (item) =>
        item.produk &&
        item.produk.id_petani &&
        String(item.produk.id_petani._id) === String(req.user.userId)
    );
    if (!isPetani) return res.status(403).json({ message: "Akses ditolak" });

    order.status_pesanan = status_pesanan;
    if (catatan) order.catatan = catatan;
    await order.save();

    res.json({ message: "Status pesanan diupdate oleh petani", order });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// 6. Get detail order by id (for admin/detail)
exports.getOrderById = async (req, res) => {
  try {
    const order = await Order.findById(req.params.id)
      .populate("id_konsumen", "nama email")
      .populate("id_titik_ambil", "nama alamat")
      .populate({
        path: "items.produk",
        populate: { path: "id_petani", select: "nama" },
      }); // INI PENTING: Nested populate!
    if (!order)
      return res.status(404).json({ message: "Order tidak ditemukan" });
    res.json(order);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
