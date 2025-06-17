const Report = require('../models/Report');
const Order = require('../models/Order');
const User = require('../models/User');
const PickupPoint = require('../models/PickupPoint');
const Product = require('../models/Product');

// Generate laporan penjualan (admin/petani)
exports.generateSalesReport = async (req, res) => {
  try {
    const { periode_awal, periode_akhir, id_petani } = req.body;

    // Query filter
    const filter = {};
    if (periode_awal && periode_akhir) {
      filter.createdAt = {
        $gte: new Date(periode_awal),
        $lte: new Date(periode_akhir),
      };
    }
    if (id_petani) {
      filter['items.id_petani'] = id_petani;
    }
    filter.status_pesanan = 'selesai';

    // Query orders
    const orders = await Order.find(filter);

    // Ringkasan laporan
    let total_transaksi = orders.length;
    let total_pendapatan = orders.reduce((sum, order) => sum + (order.total_harga || 0), 0);

    // Ambil detail produk terjual (summary per produk)
    let produkSummary = {};
    orders.forEach(order => {
      order.items.forEach(item => {
        if (!produkSummary[item.nama_produk]) {
          produkSummary[item.nama_produk] = {
            jumlah: 0,
            pendapatan: 0
          };
        }
        produkSummary[item.nama_produk].jumlah += item.jumlah;
        produkSummary[item.nama_produk].pendapatan += item.subtotal;
      });
    });

    // Simpan laporan ke database (opsional)
    const report = new Report({
      jenis: 'penjualan',
      periode: `${periode_awal} - ${periode_akhir}`,
      dibuat_oleh: req.user.userId,
      data: { produkSummary, orders },
      total_transaksi,
      total_produk: Object.keys(produkSummary).length,
      total_pendapatan,
      catatan: req.body.catatan || ''
    });
    await report.save();

    res.json({
      message: 'Laporan penjualan berhasil dibuat',
      report
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Generate laporan transaksi (admin)
exports.generateTransactionReport = async (req, res) => {
  try {
    const { periode_awal, periode_akhir } = req.body;
    const filter = {};
    if (periode_awal && periode_akhir) {
      filter.createdAt = {
        $gte: new Date(periode_awal),
        $lte: new Date(periode_akhir),
      };
    }
    const orders = await Order.find(filter);

    const report = new Report({
      jenis: 'transaksi',
      periode: `${periode_awal} - ${periode_akhir}`,
      dibuat_oleh: req.user.userId,
      data: orders,
      total_transaksi: orders.length,
      catatan: req.body.catatan || ''
    });
    await report.save();

    res.json({
      message: 'Laporan transaksi berhasil dibuat',
      report
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Ambil semua laporan
exports.getAllReports = async (req, res) => {
  try {
    const reports = await Report.find()
      .populate('dibuat_oleh', 'nama email peran')
      .sort({ createdAt: -1 });
    res.json(reports);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Ambil laporan berdasarkan id
exports.getReportById = async (req, res) => {
  try {
    const report = await Report.findById(req.params.id)
      .populate('dibuat_oleh', 'nama email peran');
    if (!report) return res.status(404).json({ message: 'Laporan tidak ditemukan' });
    res.json(report);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// (Opsional) Hapus laporan (admin)
exports.deleteReport = async (req, res) => {
  try {
    const report = await Report.findByIdAndDelete(req.params.id);
    if (!report) return res.status(404).json({ message: 'Laporan tidak ditemukan' });
    res.json({ message: 'Laporan berhasil dihapus' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
