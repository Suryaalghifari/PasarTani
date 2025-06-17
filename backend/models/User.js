const mongoose = require('mongoose');

const userSchema = new mongoose.Schema({
  nama: {
    type: String,
    required: [true, 'Nama wajib diisi'],
    trim: true,
  },
  email: {
    type: String,
    required: [true, 'Email wajib diisi'],
    unique: true,
    trim: true,
    lowercase: true,
    match: [/.+\@.+\..+/, 'Format email tidak valid'],
  },
  password: {
    type: String,
    required: [true, 'Password wajib diisi'],
    minlength: [6, 'Password minimal 6 karakter'],
  },
  peran: {
    type: String,
    enum: ['admin', 'petani', 'konsumen', 'petugas'],
    required: [true, 'Peran wajib dipilih'],
  },
  alamat: {
    type: String,
    default: '',
    trim: true,
  },
  no_hp: {
    type: String,
    default: '',
    trim: true,
  },
  foto: {
    type: String, // Path ke foto profil user di folder uploads/user/
    default: '',  // Contoh: 'uploads/user/default.jpg'
  },
  isVerified: {
    type: Boolean,
    default: false, // Untuk fitur verifikasi user (admin/petani) jika dibutuhkan
  }
}, { timestamps: true }); // Otomatis tambah createdAt & updatedAt

module.exports = mongoose.model('User', userSchema);
