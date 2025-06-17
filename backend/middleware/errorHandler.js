module.exports = (err, req, res, next) => {
    console.error('Error:', err);
    res.status(500).json({ message: 'Terjadi kesalahan pada server', error: err.message });
  };
  