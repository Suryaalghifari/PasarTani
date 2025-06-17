const express = require('express');
const router = express.Router();
const uploadController = require('../controllers/uploadController');
const upload = require('../middleware/uploadMiddleware');
const auth = require('../middleware/authMiddleware');

// Upload satu file
router.post('/single', auth, upload.single('file'), uploadController.uploadFile);

// Upload multiple files
router.post('/multiple', auth, upload.array('files', 5), uploadController.uploadMultipleFiles);

module.exports = router;
