const express = require('express');
const router = express.Router();
const reportController = require('../controllers/reportController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');

// Generate laporan penjualan
router.post('/sales', auth, role(['admin', 'petani']), reportController.generateSalesReport);

// Generate laporan transaksi
router.post('/transaction', auth, role(['admin']), reportController.generateTransactionReport);

// Get semua report
router.get('/', auth, role(['admin']), reportController.getAllReports);

// Get report by id
router.get('/:id', auth, reportController.getReportById);

// Hapus report (admin)
router.delete('/:id', auth, role(['admin']), reportController.deleteReport);

module.exports = router;
