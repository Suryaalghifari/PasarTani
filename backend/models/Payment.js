const mongoose = require('mongoose');

const paymentSchema = new mongoose.Schema({
  id_order: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Order',
    required: [true, 'Order wajib diisi'],
  },
  id_konsumen: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Konsumen wajib diisi'],
  },
  metode: {
    type: String,
    enum: ['transfer', 'gopay', 'ovo', 'cod', 'qris', 'dummy'],
    required: [true, 'Metode pembayaran wajib diisi'],
  },
  jumlah: {
    type: Number,
    required: [true, 'Jumlah pembayaran wajib diisi'],
    min: [0, 'Jumlah pembayaran tidak boleh negatif'],
  },
  status: {
    type: String,
    enum: ['pending', 'menunggu-verifikasi', 'berhasil', 'gagal'],
    default: 'pending',
  },
  tanggal_bayar: {
    type: Date,
    default: Date.now,
  },
  bukti_pembayaran: {
    type: String, // Path ke /uploads/pembayaran/ jika manual upload
    default: '',
  },
  catatan: {
    type: String,
    default: '',
    trim: true,
  }
}, { timestamps: true });

module.exports = mongoose.model('Payment', paymentSchema);
