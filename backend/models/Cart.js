const mongoose = require('mongoose');

const cartSchema = new mongoose.Schema({
  id_konsumen: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Cart harus dimiliki oleh konsumen'],
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
        min: [1, 'Minimal jumlah barang 1'],
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
  }
}, { timestamps: true });

module.exports = mongoose.model('Cart', cartSchema);
