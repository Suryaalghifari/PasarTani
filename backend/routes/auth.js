const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const { body } = require('express-validator');
const validation = require('../middleware/validation');

// Register
router.post(
  '/register',
  [
    body('nama').notEmpty(),
    body('email').isEmail(),
    body('password').isLength({ min: 6 }),
    body('peran').isIn(['admin', 'petani', 'konsumen', 'petugas']),
    validation
  ],
  authController.register
);

// Login
router.post(
  '/login',
  [
    body('email').isEmail(),
    body('password').notEmpty(),
    validation
  ],
  authController.login
);

module.exports = router;
