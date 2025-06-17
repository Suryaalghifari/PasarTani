const mongoose = require('mongoose');

const productSchema = new mongoose.Schema({
  nama: {
    type: String,
    required: [true, 'Nama produk wajib diisi'],
    trim: true,
  },
  deskripsi: {
    type: String,
    required: [true, 'Deskripsi produk wajib diisi'],
    trim: true,
  },
  kategori: {
    type: String,
    enum: ['sayur', 'buah', 'herbal', 'produk-lain'],
    required: [true, 'Kategori produk wajib dipilih'],
  },
  harga: {
    type: Number,
    required: [true, 'Harga produk wajib diisi'],
    min: [0, 'Harga tidak boleh negatif'],
  },
  stok: {
    type: Number,
    required: [true, 'Stok produk wajib diisi'],
    min: [0, 'Stok tidak boleh negatif'],
  },
  foto: {
    type: String,
    required: [true, 'Foto produk wajib di-upload'],
    // Contoh: 'uploads/produk/tomat-123.jpg'
  },
  id_petani: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Produk harus dimiliki oleh petani'],
  },
  status: {
    type: String,
    enum: ['tersedia', 'habis', 'tunggu-verifikasi', 'ditolak'],
    default: 'tunggu-verifikasi', // Default: produk baru perlu diverifikasi admin
  },
  isActive: {
    type: Boolean,
    default: true,
  }
}, { timestamps: true }); // createdAt, updatedAt

module.exports = mongoose.model('Product', productSchema);
