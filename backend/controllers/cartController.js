const Cart = require('../models/Cart');
const Product = require('../models/Product');

// Dapatkan cart milik user login (konsumen)
exports.getMyCart = async (req, res) => {
  try {
    let cart = await Cart.findOne({ id_konsumen: req.user.userId });
    if (!cart) {
      // Jika belum ada cart, buat baru
      cart = new Cart({ id_konsumen: req.user.userId, items: [], total_harga: 0 });
      await cart.save();
    }
    res.json(cart);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tambah atau update produk ke cart
exports.addOrUpdateCartItem = async (req, res) => {
  try {
    const { produk, jumlah } = req.body;

    // Cari produk
    const foundProduct = await Product.findById(produk);
    if (!foundProduct) return res.status(404).json({ message: 'Produk tidak ditemukan' });

    // Dapatkan/atau buat cart user
    let cart = await Cart.findOne({ id_konsumen: req.user.userId });
    if (!cart) {
      cart = new Cart({ id_konsumen: req.user.userId, items: [], total_harga: 0 });
    }

    // Cari item di cart, update jika ada, tambah baru jika tidak
    const itemIndex = cart.items.findIndex(item => String(item.produk) === String(produk));
    const subtotal = foundProduct.harga * jumlah;

    if (itemIndex > -1) {
      // Update item jika sudah ada di cart
      cart.items[itemIndex].jumlah = jumlah;
      cart.items[itemIndex].subtotal = subtotal;
      cart.items[itemIndex].nama_produk = foundProduct.nama;
      cart.items[itemIndex].harga = foundProduct.harga;
    } else {
      // Tambah item baru ke cart
      cart.items.push({
        produk,
        nama_produk: foundProduct.nama,
        harga: foundProduct.harga,
        jumlah,
        subtotal,
      });
    }

    // Hitung ulang total harga
    cart.total_harga = cart.items.reduce((acc, item) => acc + item.subtotal, 0);
    await cart.save();

    res.json({ message: 'Cart berhasil diupdate', cart });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Hapus item dari cart
exports.removeCartItem = async (req, res) => {
  try {
    const { produk } = req.body;
    let cart = await Cart.findOne({ id_konsumen: req.user.userId });
    if (!cart) return res.status(404).json({ message: 'Cart tidak ditemukan' });

    // Filter item yang akan dihapus
    cart.items = cart.items.filter(item => String(item.produk) !== String(produk));
    cart.total_harga = cart.items.reduce((acc, item) => acc + item.subtotal, 0);

    await cart.save();
    res.json({ message: 'Item berhasil dihapus dari cart', cart });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Bersihkan cart (setelah checkout sukses)
exports.clearCart = async (req, res) => {
  try {
    let cart = await Cart.findOne({ id_konsumen: req.user.userId });
    if (!cart) return res.status(404).json({ message: 'Cart tidak ditemukan' });

    cart.items = [];
    cart.total_harga = 0;
    await cart.save();
    res.json({ message: 'Cart berhasil dibersihkan', cart });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
