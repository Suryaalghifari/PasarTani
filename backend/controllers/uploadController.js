// Tidak butuh import model, hanya kirim response upload file

exports.uploadFile = async (req, res) => {
    try {
      if (!req.file) {
        return res.status(400).json({ message: 'Tidak ada file yang di-upload.' });
      }
      // Kembalikan path file dan info
      res.json({
        message: 'Upload berhasil.',
        file: {
          originalname: req.file.originalname,
          filename: req.file.filename,
          mimetype: req.file.mimetype,
          size: req.file.size,
          path: req.file.path, // Simpan path ke db jika ingin digunakan di field model lain
          url: `${req.protocol}://${req.get('host')}/${req.file.path.replace(/\\/g, '/')}`,
        }
      });
    } catch (error) {
      res.status(500).json({ message: error.message });
    }
  };
  
  // (Opsional) upload multiple files
  exports.uploadMultipleFiles = async (req, res) => {
    try {
      if (!req.files || !req.files.length) {
        return res.status(400).json({ message: 'Tidak ada file yang di-upload.' });
      }
      const filesInfo = req.files.map(file => ({
        originalname: file.originalname,
        filename: file.filename,
        mimetype: file.mimetype,
        size: file.size,
        path: file.path,
        url: `${req.protocol}://${req.get('host')}/${file.path.replace(/\\/g, '/')}`,
      }));
      res.json({
        message: 'Multiple file upload berhasil.',
        files: filesInfo
      });
    } catch (error) {
      res.status(500).json({ message: error.message });
    }
  };
  