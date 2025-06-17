const express = require('express');
const router = express.Router();
const checkoutController = require('../controllers/checkoutController');
const auth = require('../middleware/authMiddleware');
const upload = require('../middleware/uploadMiddleware');
const role = require('../middleware/roleMiddleware');

// [Konsumen] Buat checkout baru dari cart
router.post(
  '/',
  auth,
  role(['konsumen']),
  checkoutController.createCheckout
);

// [Konsumen] Upload bukti pembayaran (transfer/QRIS)
router.post(
  '/:id/upload-bukti',
  auth,
  upload.single('bukti_pembayaran'),
  checkoutController.uploadBuktiPembayaran
);

// [Admin] Verifikasi pembayaran checkout (ubah status, otomatis create order jika paid)
router.patch(
  '/:id/verify',
  auth,
  role(['admin']),
  checkoutController.verifyPayment
);

// [Admin] Lihat semua data checkout
router.get(
  '/',
  auth,
  role(['admin']),
  checkoutController.getAllCheckouts
);

// [Konsumen] Lihat riwayat checkout sendiri
router.get(
  '/me',
  auth,
  role(['konsumen']),
  checkoutController.getMyCheckouts
);

// [All Authenticated] Get detail checkout by ID
router.get(
  '/:id',
  auth,
  checkoutController.getCheckoutById
);

module.exports = router;
