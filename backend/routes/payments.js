const express = require('express');
const router = express.Router();
const paymentController = require('../controllers/paymentController');
const auth = require('../middleware/authMiddleware');
const upload = require('../middleware/uploadMiddleware');
const role = require('../middleware/roleMiddleware');

// Buat pembayaran (konsumen)
router.post('/', auth, role(['konsumen']), paymentController.createPayment);

// Upload bukti bayar
router.post('/:id/upload-bukti', auth, upload.single('bukti_pembayaran'), paymentController.uploadBuktiPembayaran);

// Admin: verifikasi pembayaran
router.patch('/:id/verify', auth, role(['admin']), paymentController.verifyPayment);

// Get pembayaran milik sendiri
router.get('/me', auth, paymentController.getMyPayments);

// Admin: get semua pembayaran
router.get('/', auth, role(['admin']), paymentController.getAllPayments);

// Get detail pembayaran
router.get('/:id', auth, paymentController.getPaymentById);

module.exports = router;
