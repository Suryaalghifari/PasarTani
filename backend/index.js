require('dotenv').config();
const express = require('express');
const cors = require('cors');
const connectDB = require('./config/database');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 5000;

// 1. Middleware utama
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// 2. Serve folder uploads (akses foto/file via URL)
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// 3. Koneksi ke MongoDB
connectDB();

// 4. Import & pasang semua route
app.use('/api/auth', require('./routes/auth'));
app.use('/api/users', require('./routes/users'));
app.use('/api/products', require('./routes/products'));
app.use('/api/orders', require('./routes/orders'));
app.use('/api/cart', require('./routes/cart'));
app.use('/api/checkout', require('./routes/checkout'));
app.use('/api/logistics', require('./routes/logistics'));
app.use('/api/pickup-points', require('./routes/pickupPoints'));
app.use('/api/pickup-verification', require('./routes/pickupVerification'));
app.use('/api/notifications', require('./routes/notifications'));
app.use('/api/reports', require('./routes/reports'));
app.use('/api/payments', require('./routes/payments'));
app.use('/api/upload', require('./routes/upload'));
app.use('/api/dashboard', require('./routes/dashboard'));

// 5. Tes endpoint root (opsional)
app.get('/', (req, res) => {
  res.send('Pasar Tani Digital Backend is running 🚀');
});

// 6. Error Handler Global (harus di bawah semua route)
const errorHandler = require('./middleware/errorHandler');
app.use(errorHandler);

// 7. Start server
app.listen(PORT, () => {
  console.log(`🚀 Server berjalan di http://localhost:${PORT}`);
});
