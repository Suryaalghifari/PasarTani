const mongoose = require('mongoose');

const checkoutSchema = new mongoose.Schema({
  id_konsumen: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Konsumen wajib diisi'],
  },
  cart_items: [
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
        min: [1, 'Jumlah minimal 1'],
      },
      subtotal: {
        type: Number,
        required: true,
      }
    }
  ],
  total_harga: {
    type: Number,
    required: true,
    min: [0, 'Total harga tidak boleh negatif'],
  },
  id_titik_ambil: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'PickupPoint',
    required: [true, 'Titik ambil wajib diisi'],
  },
  alamat_pengiriman: {
    type: String,
    required: [true, 'Alamat pengiriman wajib diisi'],
  },
  no_hp: {
    type: String,
    required: [true, 'No HP wajib diisi'],
  },
  metode_pembayaran: {
    type: String,
    enum: ['transfer', 'gopay', 'ovo', 'cod', 'qris'],
    required: [true, 'Metode pembayaran wajib dipilih'],
  },
  bukti_pembayaran: {
    type: String, // Path ke /uploads/pembayaran/
    default: '',
  },
  status_checkout: {
    type: String,
    enum: ['pending', 'menunggu-verifikasi', 'paid', 'gagal'],
    default: 'pending',
  },
  catatan: {
    type: String,
    default: '',
    trim: true,
  }
}, { timestamps: true });

module.exports = mongoose.model('Checkout', checkoutSchema);
