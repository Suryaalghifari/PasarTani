const Product = require('../models/Product');
const User = require('../models/User');

// Tambah produk (hanya petani)
exports.createProduct = async (req, res) => {
  try {
    const { nama, deskripsi, kategori, harga, stok } = req.body;
    const foto = req.file ? req.file.path : ''; // dari multer upload
    if (!foto) return res.status(400).json({ message: 'Foto produk wajib di-upload' });

    const newProduct = new Product({
      nama,
      deskripsi,
      kategori,
      harga,
      stok,
      foto,
      id_petani: req.user.userId,
      status: 'tunggu-verifikasi',
      isActive: true,
    });
    await newProduct.save();
    res.status(201).json({ message: 'Produk berhasil ditambahkan, menunggu verifikasi admin', product: newProduct });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get semua produk (untuk umum/dashboard)
exports.getAllProducts = async (req, res) => {
  try {
    const products = await Product.find({ status: 'tersedia', isActive: true }).populate('id_petani', 'nama email');
    res.json(products);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get produk by id
exports.getProductById = async (req, res) => {
  try {
    const product = await Product.findById(req.params.id).populate('id_petani', 'nama email');
    if (!product) return res.status(404).json({ message: 'Produk tidak ditemukan' });
    res.json(product);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get produk milik petani (dashboard petani)
exports.getProductsByPetani = async (req, res) => {
  try {
    const products = await Product.find({ id_petani: req.user.userId }).sort({ createdAt: -1 });
    res.json(products);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Update produk (petani hanya bisa update miliknya sendiri, admin bisa update apapun)
exports.updateProduct = async (req, res) => {
  try {
    const { nama, deskripsi, kategori, harga, stok } = req.body;
    const updateData = { nama, deskripsi, kategori, harga, stok };
    if (req.file) updateData.foto = req.file.path;

    let product = await Product.findById(req.params.id);
    if (!product) return res.status(404).json({ message: 'Produk tidak ditemukan' });

    // Jika petani, cek hanya boleh update produknya sendiri
    if (req.user.peran === 'petani' && product.id_petani.toString() !== req.user.userId) {
      return res.status(403).json({ message: 'Tidak punya akses untuk update produk ini' });
    }

    // Set status jadi tunggu-verifikasi jika petani update
    if (req.user.peran === 'petani') updateData.status = 'tunggu-verifikasi';

    product = await Product.findByIdAndUpdate(req.params.id, { $set: updateData }, { new: true, runValidators: true });
    res.json({ message: 'Produk berhasil diupdate', product });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Hapus produk (petani hanya bisa hapus miliknya sendiri, admin bisa hapus apapun)
exports.deleteProduct = async (req, res) => {
  try {
    let product = await Product.findById(req.params.id);
    if (!product) return res.status(404).json({ message: 'Produk tidak ditemukan' });

    // Jika petani, cek hanya boleh hapus produknya sendiri
    if (req.user.peran === 'petani' && product.id_petani.toString() !== req.user.userId) {
      return res.status(403).json({ message: 'Tidak punya akses untuk hapus produk ini' });
    }

    await Product.findByIdAndDelete(req.params.id);
    res.json({ message: 'Produk berhasil dihapus' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Admin: Verifikasi produk (ubah status)
exports.verifyProduct = async (req, res) => {
  try {
    const { status } = req.body; // status: 'tersedia', 'ditolak'
    const product = await Product.findByIdAndUpdate(
      req.params.id,
      { status },
      { new: true, runValidators: true }
    );
    if (!product) return res.status(404).json({ message: 'Produk tidak ditemukan' });
    res.json({ message: `Status produk diubah menjadi ${status}`, product });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
