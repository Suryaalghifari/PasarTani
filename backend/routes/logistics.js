const express = require('express');
const router = express.Router();
const logisticsController = require('../controllers/logisticsController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');

// Buat data logistik (admin/logistik)
router.post('/', auth, role(['admin', 'petugas']), logisticsController.createLogistics);

// Get semua logistics
router.get('/', auth, role(['admin', 'petugas']), logisticsController.getAllLogistics);

// Get logistics untuk 1 order
router.get('/order/:orderId', auth, logisticsController.getLogisticsByOrder);

// Update status logistics
router.patch('/:id/status', auth, role(['admin', 'petugas']), logisticsController.updateLogisticsStatus);

// Get logistics untuk petani
router.get('/petani/me', auth, role(['petani']), logisticsController.getLogisticsForPetani);

module.exports = router;
