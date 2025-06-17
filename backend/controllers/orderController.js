const Order = require('../models/Order');
const Product = require('../models/Product');
const PickupPoint = require('../models/PickupPoint');

// Buat order baru (Checkout)
exports.createOrder = async (req, res) => {
  try {
    const {
      items,        // array of {produk, nama_produk, harga, jumlah, subtotal}
      total_harga,
      alamat_pengiriman,
      no_hp,
      id_titik_ambil,
      metode_pembayaran,
      catatan
    } = req.body;

    // Validasi: Minimal 1 item, semua produk harus valid
    if (!items || !Array.isArray(items) || items.length === 0) {
      return res.status(400).json({ message: 'Order harus berisi minimal 1 produk' });
    }

    // Pastikan titik ambil valid
    const pickupPoint = await PickupPoint.findById(id_titik_ambil);
    if (!pickupPoint || pickupPoint.status !== 'aktif') {
      return res.status(400).json({ message: 'Titik pengambilan tidak valid/aktif' });
    }

    // Cek stok untuk tiap produk
    for (let item of items) {
      const produk = await Product.findById(item.produk);
      if (!produk) return res.status(400).json({ message: `Produk ${item.nama_produk} tidak ditemukan` });
      if (produk.stok < item.jumlah) {
        return res.status(400).json({ message: `Stok produk ${item.nama_produk} tidak mencukupi` });
      }
    }

    // Buat order baru
    const order = new Order({
      id_konsumen: req.user.userId,
      items,
      total_harga,
      alamat_pengiriman,
      no_hp,
      id_titik_ambil,
      metode_pembayaran,
      catatan,
      // status_pembayaran dan status_pesanan default
    });
    await order.save();

    // Kurangi stok produk
    for (let item of items) {
      await Product.findByIdAndUpdate(item.produk, { $inc: { stok: -item.jumlah } });
    }

    res.status(201).json({ message: 'Order berhasil dibuat', order });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get semua order (admin) / Get order by filter
exports.getAllOrders = async (req, res) => {
  try {
    // Optional: Tambah filter by status/metode/dll via req.query
    const orders = await Order.find()
      .populate('id_konsumen', 'nama email')
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });
    res.json(orders);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get order milik user login (konsumen)
exports.getMyOrders = async (req, res) => {
  try {
    const orders = await Order.find({ id_konsumen: req.user.userId })
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });
    res.json(orders);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get order untuk produk petani (petani dashboard)
exports.getOrdersForPetani = async (req, res) => {
  try {
    // Cari order yang di dalam items ada produk milik petani login
    const orders = await Order.find({ 'items.produk': { $exists: true } })
      .populate('id_konsumen', 'nama email')
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });

    // Filter hanya order yang produknya milik petani login
    const filteredOrders = orders.filter(order =>
      order.items.some(item => String(item.produk) === String(req.user.userId))
    );
    res.json(filteredOrders);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Update status pembayaran/order (admin/petugas/petani, sesuai role & fitur)
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
    if (!order) return res.status(404).json({ message: 'Order tidak ditemukan' });

    res.json({ message: 'Status order berhasil diupdate', order });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get detail order by id
exports.getOrderById = async (req, res) => {
  try {
    const order = await Order.findById(req.params.id)
      .populate('id_konsumen', 'nama email')
      .populate('id_titik_ambil', 'nama alamat');
    if (!order) return res.status(404).json({ message: 'Order tidak ditemukan' });
    res.json(order);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

