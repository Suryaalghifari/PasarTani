const express = require('express');
const router = express.Router();
const pickupPointController = require('../controllers/pickupPointController');
const auth = require('../middleware/authMiddleware');
const role = require('../middleware/roleMiddleware');

// CRUD pickup point (admin)
router.post('/', auth, role(['admin']), pickupPointController.createPickupPoint);
router.get('/', pickupPointController.getAllPickupPoints);
router.get('/active', pickupPointController.getActivePickupPoints);
router.get('/:id', pickupPointController.getPickupPointById);
router.put('/:id', auth, role(['admin']), pickupPointController.updatePickupPoint);
router.delete('/:id', auth, role(['admin']), pickupPointController.deletePickupPoint);

module.exports = router;
