const express = require('express');
const router = express.Router();
const productController = require('../controllers/productController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');
const upload = require('../middleware/uploadMiddleware');

// List produk publik
router.get('/', productController.getAllProducts);

// Tambah produk (petani)
router.post(
  '/',
  auth,
  role(['petani']),
  upload.single('foto'),
  productController.createProduct
);

// Get produk detail
router.get('/:id', productController.getProductById);

// Get produk milik petani sendiri
router.get('/petani/me', auth, role(['petani']), productController.getProductsByPetani);

// Update produk (petani/admin)
router.put(
  '/:id',
  auth,
  role(['petani', 'admin']),
  upload.single('foto'),
  productController.updateProduct
);

// Hapus produk
router.delete('/:id', auth, role(['petani', 'admin']), productController.deleteProduct);

// Admin: Verifikasi produk
router.patch(
  '/:id/verify',
  auth,
  role(['admin']),
  productController.verifyProduct
);

module.exports = router;
