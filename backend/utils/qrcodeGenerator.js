const QRCode = require('qrcode');

// Generate QR Code dari string/data (async)
exports.generateQRCode = async (text) => {
  try {
    // Bisa return data:image/png;base64 atau simpan file
    return await QRCode.toDataURL(text);
  } catch (err) {
    throw err;
  }
};

// Simpan QR code ke file
exports.generateQRCodeToFile = async (text, path) => {
  try {
    await QRCode.toFile(path, text);
    return path;
  } catch (err) {
    throw err;
  }
};
