const express = require('express');
const router = express.Router();
const orderController = require('../controllers/orderController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');

// Konsumen: buat order
router.post('/', auth, role(['konsumen']), orderController.createOrder);

// Admin: lihat semua order
router.get('/', auth, role(['admin']), orderController.getAllOrders);

// Konsumen: lihat order sendiri
router.get('/me', auth, role(['konsumen']), orderController.getMyOrders);

// Petani: lihat order terkait produknya
router.get('/petani/me', auth, role(['petani']), orderController.getOrdersForPetani);

// Update status order (admin/petugas/petani, sesuai role)
router.patch('/:id/status', auth, role(['admin', 'petugas', 'petani']), orderController.updateOrderStatus);

// Get detail order
router.get('/:id', auth, orderController.getOrderById);

module.exports = router;
