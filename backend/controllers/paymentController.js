const Payment = require('../models/Payment');
const Order = require('../models/Order');
const fs = require('fs');

// Buat pembayaran baru (setelah order dibuat)
exports.createPayment = async (req, res) => {
  try {
    const { id_order, metode, jumlah, catatan } = req.body;
    const id_konsumen = req.user.userId;

    // Validasi order
    const order = await Order.findById(id_order);
    if (!order) return res.status(404).json({ message: 'Order tidak ditemukan' });

    // Optional: Cek sudah ada pembayaran?
    let payment = await Payment.findOne({ id_order });
    if (payment) {
      return res.status(400).json({ message: 'Pembayaran untuk order ini sudah ada.' });
    }

    payment = new Payment({
      id_order,
      id_konsumen,
      metode,
      jumlah,
      catatan,
      status: metode === 'cod' ? 'berhasil' : 'pending', // Jika COD langsung berhasil
    });

    await payment.save();

    // Jika COD, update order ke paid
    if (metode === 'cod') {
      order.status_pembayaran = 'paid';
      await order.save();
    }

    res.status(201).json({ message: 'Pembayaran berhasil dicatat.', payment });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Upload bukti pembayaran (untuk metode transfer/QRIS manual)
exports.uploadBuktiPembayaran = async (req, res) => {
  try {
    const payment = await Payment.findById(req.params.id);
    if (!payment) return res.status(404).json({ message: 'Data pembayaran tidak ditemukan' });

    // Upload file bukti
    const file = req.file;
    if (!file) return res.status(400).json({ message: 'Bukti pembayaran wajib di-upload.' });

    // Hapus file lama jika ada
    if (payment.bukti_pembayaran && fs.existsSync(payment.bukti_pembayaran)) {
      fs.unlinkSync(payment.bukti_pembayaran);
    }

    payment.bukti_pembayaran = file.path;
    payment.status = 'menunggu-verifikasi';
    await payment.save();

    res.json({ message: 'Bukti pembayaran berhasil di-upload.', payment });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Verifikasi pembayaran oleh admin
exports.verifyPayment = async (req, res) => {
  try {
    const { status } = req.body; // status: 'berhasil' atau 'gagal'
    const payment = await Payment.findById(req.params.id);
    if (!payment) return res.status(404).json({ message: 'Data pembayaran tidak ditemukan' });

    payment.status = status;
    await payment.save();

    // Jika berhasil, update status order
    if (status === 'berhasil') {
      const order = await Order.findById(payment.id_order);
      if (order) {
        order.status_pembayaran = 'paid';
        await order.save();
      }
    }

    res.json({ message: `Status pembayaran diubah menjadi ${status}`, payment });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get pembayaran milik user (konsumen)
exports.getMyPayments = async (req, res) => {
  try {
    const payments = await Payment.find({ id_konsumen: req.user.userId })
      .populate('id_order')
      .sort({ createdAt: -1 });
    res.json(payments);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Admin: Get semua data pembayaran
exports.getAllPayments = async (req, res) => {
  try {
    const payments = await Payment.find()
      .populate('id_konsumen', 'nama email')
      .populate('id_order')
      .sort({ createdAt: -1 });
    res.json(payments);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get detail pembayaran by id
exports.getPaymentById = async (req, res) => {
  try {
    const payment = await Payment.findById(req.params.id)
      .populate('id_konsumen', 'nama email')
      .populate('id_order');
    if (!payment) return res.status(404).json({ message: 'Data pembayaran tidak ditemukan.' });
    res.json(payment);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
