const User = require('../models/User');
const Product = require('../models/Product');
const Order = require('../models/Order');
const Notification = require('../models/Notification');
const PickupPoint = require('../models/PickupPoint');
const Logistics = require('../models/Logistics');

// Dashboard Admin: statistik seluruh data sistem
exports.adminDashboard = async (req, res) => {
  try {
    const [totalUsers, totalPetani, totalKonsumen, totalProduk, totalOrder, totalPickupPoint] = await Promise.all([
      User.countDocuments(),
      User.countDocuments({ peran: 'petani' }),
      User.countDocuments({ peran: 'konsumen' }),
      Product.countDocuments(),
      Order.countDocuments(),
      PickupPoint.countDocuments()
    ]);
    const totalPendapatan = await Order.aggregate([
      { $match: { status_pesanan: 'selesai' } },
      { $group: { _id: null, total: { $sum: '$total_harga' } } }
    ]);
    const unreadNotif = await Notification.countDocuments({ user: req.user.userId, isRead: false });

    res.json({
      totalUsers,
      totalPetani,
      totalKonsumen,
      totalProduk,
      totalOrder,
      totalPickupPoint,
      totalPendapatan: totalPendapatan[0]?.total || 0,
      unreadNotif
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Dashboard Petani: statistik penjualan, produk sendiri, order produk sendiri
exports.petaniDashboard = async (req, res) => {
  try {
    const id_petani = req.user.userId;
    const [produkSaya, orderSaya] = await Promise.all([
      Product.find({ id_petani }),
      Order.find({ 'items.produk': { $exists: true } }) // order yang ada produk milik petani ini
    ]);
    // Filter hanya order yang memuat produk milik petani login
    const orderFiltered = orderSaya.filter(order =>
      order.items.some(item => String(item.produk) === String(id_petani))
    );
    // Pendapatan total
    let totalPendapatan = 0;
    orderFiltered.forEach(order => {
      order.items.forEach(item => {
        if (String(item.produk) === String(id_petani)) {
          totalPendapatan += item.subtotal;
        }
      });
    });

    const unreadNotif = await Notification.countDocuments({ user: id_petani, isRead: false });

    res.json({
      totalProdukSaya: produkSaya.length,
      totalOrderProdukSaya: orderFiltered.length,
      totalPendapatan,
      unreadNotif
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Dashboard Konsumen: statistik order, total belanja, notifikasi
exports.konsumenDashboard = async (req, res) => {
  try {
    const id_konsumen = req.user.userId;
    const orderSaya = await Order.find({ id_konsumen });
    const totalBelanja = orderSaya.reduce((sum, order) => sum + (order.total_harga || 0), 0);
    const unreadNotif = await Notification.countDocuments({ user: id_konsumen, isRead: false });

    res.json({
      totalOrder: orderSaya.length,
      totalBelanja,
      unreadNotif
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Dashboard Petugas Titik Pengambilan: statistik order masuk hari ini, verifikasi pickup
exports.petugasDashboard = async (req, res) => {
  try {
    const id_petugas = req.user.userId;
    const today = new Date();
    today.setHours(0,0,0,0);
    const besok = new Date(today);
    besok.setDate(besok.getDate() + 1);

    // Jumlah order yang dijadwalkan di titik pengambilan milik petugas hari ini
    const logisticsHariIni = await Logistics.find({
      jadwal_pengiriman: { $gte: today, $lt: besok }
    });
    // Bisa filter lagi jika petugas assign ke pickupPoint tertentu
    const unreadNotif = await Notification.countDocuments({ user: id_petugas, isRead: false });

    res.json({
      totalOrderHariIni: logisticsHariIni.length,
      unreadNotif
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
