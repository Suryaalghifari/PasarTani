const mongoose = require('mongoose');

const notificationSchema = new mongoose.Schema({
  user: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Penerima notifikasi wajib diisi'],
  },
  tipe: {
    type: String,
    enum: [
      'order',           // Notifikasi terkait pesanan
      'pembayaran',      // Notifikasi terkait pembayaran
      'pengiriman',      // Notifikasi terkait logistik/pengiriman
      'pickup',          // Notifikasi terkait pickup point
      'promo',           // Notifikasi promo/umum
      'verifikasi',      // Notifikasi verifikasi produk/user
      'lainnya'
    ],
    default: 'lainnya',
  },
  judul: {
    type: String,
    required: [true, 'Judul notifikasi wajib diisi'],
  },
  pesan: {
    type: String,
    required: [true, 'Pesan notifikasi wajib diisi'],
  },
  isRead: {
    type: Boolean,
    default: false,
  },
  dataTerkait: {
    type: mongoose.Schema.Types.Mixed, // Bisa simpan ID Order/Product/PickupPoint, dll
    default: null,
  }
}, { timestamps: true });

module.exports = mongoose.model('Notification', notificationSchema);
