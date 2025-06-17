const express = require('express');
const router = express.Router();
const notificationController = require('../controllers/notificationController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');

// Kirim notifikasi (admin/sistem)
router.post('/', auth, role(['admin']), notificationController.createNotification);

// Get notifikasi milik user login
router.get('/me', auth, notificationController.getMyNotifications);

// Tandai sudah dibaca
router.patch('/:id/read', auth, notificationController.markAsRead);

// Admin: get semua notifikasi
router.get('/', auth, role(['admin']), notificationController.getAllNotifications);

// Hapus notifikasi (admin atau user sendiri)
router.delete('/:id', auth, notificationController.deleteNotification);

module.exports = router;
