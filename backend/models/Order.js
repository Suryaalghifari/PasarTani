const mongoose = require('mongoose');

const orderSchema = new mongoose.Schema({
  id_checkout: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Checkout',
    required: true, // Penting agar trace ke asal checkout
  },
  id_konsumen: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Order harus ada konsumen'],
  },
  items: [
    {
      produk: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Product',
        required: true,
      },
      nama_produk: {
        type: String,
        required: true,
      },
      harga: {
        type: Number,
        required: true,
      },
      jumlah: {
        type: Number,
        required: true,
        min: [1, 'Minimal 1 barang per produk'],
      },
      subtotal: {
        type: Number,
        required: true,
      },
    },
  ],
  total_harga: {
    type: Number,
    required: true,
    min: [0, 'Total harga tidak boleh negatif'],
  },
  alamat_pengiriman: {
    type: String,
    required: [true, 'Alamat pengiriman wajib diisi'],
  },
  no_hp: {
    type: String,
    required: [true, 'No HP wajib diisi'],
  },
  id_titik_ambil: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'PickupPoint',
    required: [true, 'Titik pengambilan wajib dipilih'],
  },
  metode_pembayaran: {
    type: String,
    enum: ['transfer', 'gopay', 'ovo', 'cod', 'qris'],
    required: [true, 'Metode pembayaran wajib dipilih'],
  },
  status_pembayaran: {
    type: String,
    enum: ['pending', 'paid', 'gagal'],
    default: 'pending',
  },
  status_pesanan: {
    type: String,
    enum: [
      'menunggu',      // Baru checkout
      'menunggu-proses', // Disarankan: default ketika order auto-create
      'diproses',
      'dikirim',
      'siap-diambil',
      'selesai',
      'dibatalkan'
    ],
    default: 'menunggu-proses',
  },
  bukti_pembayaran: {
    type: String, // Path ke file/foto di /uploads/pembayaran/
    default: '',
  },
  catatan: {
    type: String,
    default: '',
  }
}, { timestamps: true });

module.exports = mongoose.model('Order', orderSchema);
