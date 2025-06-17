const mongoose = require('mongoose');

const logisticsSchema = new mongoose.Schema({
  id_order: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Order',
    required: [true, 'Order wajib diisi'],
  },
  id_petani: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Petani wajib diisi'],
  },
  id_titik_ambil: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'PickupPoint',
    required: [true, 'Titik pengambilan wajib diisi'],
  },
  jadwal_pengambilan: {
    type: Date,
    required: [true, 'Jadwal pengambilan wajib diisi'],
  },
  jadwal_pengiriman: {
    type: Date,
    required: [true, 'Jadwal pengiriman wajib diisi'],
  },
  status_pengiriman: {
    type: String,
    enum: [
      'menunggu',
      'dalam-perjalanan',
      'tiba-di-titik-ambil',
      'selesai',
      'gagal'
    ],
    default: 'menunggu',
  },
  kurir: {
    type: String,
    default: '',
    trim: true, // Nama petugas atau jasa pengiriman (optional)
  },
  catatan: {
    type: String,
    default: '',
    trim: true,
  }
}, { timestamps: true });

module.exports = mongoose.model('Logistics', logisticsSchema);
