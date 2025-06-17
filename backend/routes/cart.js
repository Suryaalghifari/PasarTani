const express = require('express');
const router = express.Router();
const cartController = require('../controllers/cartController');
const auth = require('../middleware/authMiddleware');

// Dapatkan cart sendiri
router.get('/me', auth, cartController.getMyCart);

// Tambah/update produk ke cart
router.post('/add', auth, cartController.addOrUpdateCartItem);

// Hapus item dari cart
router.delete('/remove', auth, cartController.removeCartItem);

// Bersihkan cart
router.delete('/clear', auth, cartController.clearCart);

module.exports = router;
