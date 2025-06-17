module.exports = (roles = []) => {
    return (req, res, next) => {
      // Jika hanya 1 role, jadikan array
      if (typeof roles === 'string') roles = [roles];
      if (!roles.includes(req.user.peran)) {
        return res.status(403).json({ message: 'Akses ditolak (bukan role yang diizinkan).' });
      }
      next();
    };
  };
  