const mongoose = require('mongoose');

const pickupVerificationSchema = new mongoose.Schema({
  id_order: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Order',
    required: [true, 'Order harus diisi'],
  },
  id_petugas: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Petugas wajib diisi'],
  },
  id_konsumen: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Konsumen wajib diisi'],
  },
  id_titik_ambil: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'PickupPoint',
    required: [true, 'Titik pengambilan wajib diisi'],
  },
  tanggal_verifikasi: {
    type: Date,
    required: [true, 'Tanggal verifikasi wajib diisi'],
    default: Date.now,
  },
  waktu_verifikasi: {
    type: String,
    required: [true, 'Waktu verifikasi wajib diisi'],
    // Format: "14:30" (opsional, tergantung frontend)
  },
  foto_bukti: {
    type: String, // Path ke /uploads/verifikasi/
    required: [true, 'Foto bukti serah terima wajib di-upload'],
  },
  keterangan: {
    type: String,
    default: '',
    trim: true,
  },
  status_verifikasi: {
    type: String,
    enum: ['berhasil', 'gagal'],
    default: 'berhasil',
  }
}, { timestamps: true });

module.exports = mongoose.model('PickupVerification', pickupVerificationSchema);
