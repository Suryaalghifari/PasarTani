const express = require('express');
const router = express.Router();
const pickupVerificationController = require('../controllers/pickupVerificationController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');
const upload = require('../middleware/uploadMiddleware');

// Buat verifikasi (petugas)
router.post(
  '/',
  auth,
  role(['petugas']),
  upload.single('foto_bukti'),
  pickupVerificationController.createPickupVerification
);

// Get semua verifikasi (admin/petugas)
router.get('/', auth, role(['admin', 'petugas']), pickupVerificationController.getAllPickupVerifications);

// Get verifikasi per order
router.get('/order/:orderId', auth, pickupVerificationController.getPickupVerificationByOrder);

// Update verifikasi (petugas/admin)
router.put(
  '/:id',
  auth,
  role(['petugas', 'admin']),
  upload.single('foto_bukti'),
  pickupVerificationController.updatePickupVerification
);

// Hapus verifikasi (admin)
router.delete('/:id', auth, role(['admin']), pickupVerificationController.deletePickupVerification);

module.exports = router;
