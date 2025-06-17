module.exports = (err, req, res, next) => {
    if (err.name === 'ValidationError') {
      return res.status(400).json({ message: err.message, errors: err.errors });
    }
    next(err);
  };
  