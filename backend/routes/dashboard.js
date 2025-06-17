const express = require('express');
const router = express.Router();
const dashboardController = require('../controllers/dashboardController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');

// Admin dashboard
router.get('/admin', auth, role(['admin']), dashboardController.adminDashboard);

// Petani dashboard
router.get('/petani', auth, role(['petani']), dashboardController.petaniDashboard);

// Konsumen dashboard
router.get('/konsumen', auth, role(['konsumen']), dashboardController.konsumenDashboard);

// Petugas dashboard
router.get('/petugas', auth, role(['petugas']), dashboardController.petugasDashboard);

module.exports = router;
