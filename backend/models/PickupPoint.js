const mongoose = require('mongoose');

const pickupPointSchema = new mongoose.Schema({
  nama: {
    type: String,
    required: [true, 'Nama titik pengambilan wajib diisi'],
    trim: true,
  },
  alamat: {
    type: String,
    required: [true, 'Alamat titik pengambilan wajib diisi'],
    trim: true,
  },
  jam_operasional: {
    type: String,
    required: [true, 'Jam operasional wajib diisi'],
    // Contoh: "08:00 - 17:00"
  },
  kontak: {
    type: String,
    default: '',
    trim: true,
  },
  status: {
    type: String,
    enum: ['aktif', 'non-aktif'],
    default: 'aktif',
  },
  keterangan: {
    type: String,
    default: '',
    trim: true,
  }
}, { timestamps: true });

module.exports = mongoose.model('PickupPoint', pickupPointSchema);
