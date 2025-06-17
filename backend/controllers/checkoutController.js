const Checkout = require('../models/Checkout');
const Cart = require('../models/Cart');
const PickupPoint = require('../models/PickupPoint');
const Order = require('../models/Order');
const fs = require('fs');

// Proses checkout dari cart
exports.createCheckout = async (req, res) => {
    try {
      const cart = await Cart.findOne({ id_konsumen: req.user.userId });
      if (!cart || !cart.items.length) {
        return res.status(400).json({ message: 'Cart masih kosong.' });
      }
  
      const { id_titik_ambil, alamat_pengiriman, no_hp, metode_pembayaran, catatan } = req.body;
  
      // Pastikan pickup point aktif
      const pickupPoint = await PickupPoint.findById(id_titik_ambil);
      if (!pickupPoint || pickupPoint.status !== 'aktif') {
        return res.status(400).json({ message: 'Titik pengambilan tidak valid/aktif' });
      }
  
      const checkout = new Checkout({
        id_konsumen: req.user.userId,
        cart_items: cart.items,
        total_harga: cart.total_harga,
        id_titik_ambil,
        alamat_pengiriman,
        no_hp,
        metode_pembayaran,
        catatan
      });
  
      await checkout.save();
  
      // Kosongkan cart setelah checkout berhasil
      cart.items = [];
      cart.total_harga = 0;
      await cart.save();
  
      res.status(201).json({ message: 'Checkout berhasil. Silakan lanjut pembayaran.', checkout });
    } catch (error) {
      res.status(500).json({ message: error.message });
    }
  };
  

// Upload bukti pembayaran (manual, transfer/QRIS)
exports.uploadBuktiPembayaran = async (req, res) => {
  try {
    const { id } = req.params; // id = id checkout
    const file = req.file;

    if (!file) {
      return res.status(400).json({ message: 'Bukti pembayaran wajib di-upload.' });
    }

    const checkout = await Checkout.findById(id);
    if (!checkout) return res.status(404).json({ message: 'Data checkout tidak ditemukan.' });

    // Jika sudah ada bukti lama, hapus file lama
    if (checkout.bukti_pembayaran && fs.existsSync(checkout.bukti_pembayaran)) {
      fs.unlinkSync(checkout.bukti_pembayaran);
    }

    checkout.bukti_pembayaran = file.path;
    checkout.status_checkout = 'menunggu-verifikasi';
    await checkout.save();

    res.json({ message: 'Bukti pembayaran berhasil di-upload, menunggu verifikasi admin.', checkout });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Admin verifikasi pembayaran & otomatis create Order
// Admin verifikasi pembayaran & otomatis create Order
exports.verifyPayment = async (req, res) => {
    try {
      const { id } = req.params;
      const { status_checkout } = req.body; // 'paid' atau 'gagal'
      const checkout = await Checkout.findById(id);
      if (!checkout) return res.status(404).json({ message: 'Data checkout tidak ditemukan.' });
  
      checkout.status_checkout = status_checkout;
      await checkout.save();
  
      // ===== Tambahan logic otomatis CREATE ORDER setelah status "paid" =====
      let existingOrder = await Order.findOne({ id_checkout: checkout._id });
      if (status_checkout === "paid" && !existingOrder) {
        // *** Tambahkan field metode_pembayaran! ***
        await Order.create({
          id_checkout: checkout._id,
          id_konsumen: checkout.id_konsumen,
          items: checkout.cart_items,
          total_harga: checkout.total_harga,
          id_titik_ambil: checkout.id_titik_ambil,
          alamat_pengiriman: checkout.alamat_pengiriman,
          no_hp: checkout.no_hp,
          metode_pembayaran: checkout.metode_pembayaran, // <--- FIX INI
          status_pesanan: "menunggu-proses",
          catatan: checkout.catatan || "",
        });
      }
      // ======================================================================
  
      res.json({ message: `Status pembayaran diubah menjadi ${status_checkout}`, checkout });
    } catch (error) {
      res.status(500).json({ message: error.message });
    }
  };
  

// Lihat riwayat checkout sendiri
exports.getMyCheckouts = async (req, res) => {
  try {
    const checkouts = await Checkout.find({ id_konsumen: req.user.userId })
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });
    res.json(checkouts);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Admin: Get semua data checkout
exports.getAllCheckouts = async (req, res) => {
  try {
    const checkouts = await Checkout.find()
      .populate('id_konsumen', 'nama email')
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });
    res.json(checkouts);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get detail checkout by id
exports.getCheckoutById = async (req, res) => {
  try {
    const checkout = await Checkout.findById(req.params.id)
      .populate('id_konsumen', 'nama email')
      .populate('id_titik_ambil', 'nama alamat');
    if (!checkout) return res.status(404).json({ message: 'Data checkout tidak ditemukan.' });
    res.json(checkout);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

