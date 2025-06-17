const express = require("express");
const router = express.Router();
const multer = require("multer");
const path = require("path");
const userController = require("../controllers/userController");
const auth = require("../middleware/authMiddleware");
const role = require("../middleware/roleMiddleware");

// Multer setup
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    // Ambil role dari req.user, fallback ke 'umum' kalau tidak ada
    let folder = "uploads/umum";
    if (req.user && req.user.peran) {
      folder = `uploads/${req.user.peran}`;
    }
    // Pastikan folder ada
    const fs = require("fs");
    if (!fs.existsSync(folder)) {
      fs.mkdirSync(folder, { recursive: true });
    }
    cb(null, folder);
  },
  filename: function (req, file, cb) {
    // Nama file unik: timestamp-originalname
    cb(null, Date.now() + "-" + file.originalname.replace(/\s/g, "_"));
  },
});
const upload = multer({ storage: storage });

// Semua user (admin only)
router.get("/", auth, role(["admin"]), userController.getAllUsers);

// Get profile sendiri
router.get("/me", auth, userController.getMyProfile);

// Update profile sendiri (pakai multer!)
router.put("/me", auth, upload.single("foto"), userController.updateMyProfile);

// Detail user by id (admin or self)
router.get("/:id", auth, userController.getUserById);

router.post("/:id", auth, upload.single("foto"), userController.updateUser);

// Update user by id (pakai multer!)

router.put("/:id", auth, upload.single("foto"), userController.updateUser);

// Delete user (admin only)
router.delete("/:id", auth, role(["admin"]), userController.deleteUser);

module.exports = router;
