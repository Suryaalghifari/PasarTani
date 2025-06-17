const multer = require("multer");
const path = require("path");
const fs = require("fs");

// Fungsi helper untuk buat folder upload otomatis jika belum ada
function ensureDirExistence(dir) {
  if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
}

// Storage: folder sesuai field tujuan (produk, pembayaran, verifikasi, dll)
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    let dir = "uploads/umum";
    // Penentuan folder otomatis sesuai endpoint atau field name
    if (file.fieldname.includes("produk")) dir = "uploads/produk";
    if (file.fieldname.includes("pembayaran")) dir = "uploads/pembayaran";
    if (file.fieldname.includes("verifikasi")) dir = "uploads/verifikasi";
    ensureDirExistence(dir);
    cb(null, dir);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + "-" + Math.round(Math.random() * 1e9);
    cb(null, uniqueSuffix + "-" + file.originalname.replace(/\s/g, "_"));
  },
});

// Filter: hanya izinkan file gambar
function fileFilter(req, file, cb) {
  const allowedTypes = /jpeg|jpg|png|gif|webp/;
  const ext = path.extname(file.originalname).toLowerCase();
  if (allowedTypes.test(ext)) {
    cb(null, true);
  } else {
    cb(new Error("Hanya file gambar yang diizinkan!"), false);
  }
}

const upload = multer({
  storage,
  fileFilter,
  limits: { fileSize: 2 * 1024 * 1024 }, // max 2MB
});

module.exports = upload;
