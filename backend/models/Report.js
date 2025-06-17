const mongoose = require('mongoose');

const reportSchema = new mongoose.Schema({
  jenis: {
    type: String,
    enum: [
      'penjualan',       // Laporan penjualan produk (petani, admin)
      'transaksi',       // Laporan transaksi/pemesanan
      'pickup',          // Laporan pengambilan barang
      'produk',          // Laporan produk
      'pengguna',        // Laporan pengguna
      'logistik',        // Laporan logistik/pengiriman
      'custom'           // Custom laporan sesuai filter
    ],
    required: [true, 'Jenis laporan wajib diisi'],
  },
  periode: {
    type: String,
    required: [true, 'Periode laporan wajib diisi'],
    // Contoh: "01/01/2025 - 31/01/2025"
  },
  dibuat_oleh: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Pembuat laporan wajib diisi'],
  },
  data: {
    type: mongoose.Schema.Types.Mixed, // Data hasil report (array, object, dsb)
    required: [true, 'Data laporan wajib diisi'],
  },
  total_transaksi: {
    type: Number,
    default: 0,
  },
  total_produk: {
    type: Number,
    default: 0,
  },
  total_pendapatan: {
    type: Number,
    default: 0,
  },
  catatan: {
    type: String,
    default: '',
    trim: true,
  }
}, { timestamps: true });

module.exports = mongoose.model('Report', reportSchema);
