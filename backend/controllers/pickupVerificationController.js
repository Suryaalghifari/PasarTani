const PickupVerification = require('../models/PickupVerification');
const Order = require('../models/Order');
const PickupPoint = require('../models/PickupPoint');
const User = require('../models/User');
const fs = require('fs');

// Petugas: Upload & simpan verifikasi pickup (dengan foto bukti)
exports.createPickupVerification = async (req, res) => {
  try {
    const { id_order, id_konsumen, id_titik_ambil, tanggal_verifikasi, waktu_verifikasi, keterangan, status_verifikasi } = req.body;
    const id_petugas = req.user.userId;
    const foto_bukti = req.file ? req.file.path : '';

    if (!foto_bukti) return res.status(400).json({ message: 'Foto bukti wajib di-upload' });

    // Validasi
    const order = await Order.findById(id_order);
    if (!order) return res.status(404).json({ message: 'Order tidak ditemukan' });
    const konsumen = await User.findById(id_konsumen);
    if (!konsumen) return res.status(404).json({ message: 'Konsumen tidak ditemukan' });
    const titikAmbil = await PickupPoint.findById(id_titik_ambil);
    if (!titikAmbil) return res.status(404).json({ message: 'Titik ambil tidak ditemukan' });

    const pickupVerification = new PickupVerification({
      id_order,
      id_petugas,
      id_konsumen,
      id_titik_ambil,
      tanggal_verifikasi: tanggal_verifikasi || new Date(),
      waktu_verifikasi,
      foto_bukti,
      keterangan,
      status_verifikasi: status_verifikasi || 'berhasil'
    });
    await pickupVerification.save();

    // Update status order ke selesai jika verifikasi berhasil
    if (pickupVerification.status_verifikasi === 'berhasil') {
      order.status_pesanan = 'selesai';
      await order.save();
    }

    res.status(201).json({ message: 'Verifikasi pickup berhasil disimpan', pickupVerification });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get semua data verifikasi pickup (admin/petugas)
exports.getAllPickupVerifications = async (req, res) => {
  try {
    const list = await PickupVerification.find()
      .populate('id_order')
      .populate('id_petugas', 'nama')
      .populate('id_konsumen', 'nama')
      .populate('id_titik_ambil', 'nama alamat')
      .sort({ createdAt: -1 });
    res.json(list);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get verifikasi pickup untuk 1 order (tracking/riwayat)
exports.getPickupVerificationByOrder = async (req, res) => {
  try {
    const verif = await PickupVerification.findOne({ id_order: req.params.orderId })
      .populate('id_order')
      .populate('id_petugas', 'nama')
      .populate('id_konsumen', 'nama')
      .populate('id_titik_ambil', 'nama alamat');
    if (!verif) return res.status(404).json({ message: 'Verifikasi pickup tidak ditemukan' });
    res.json(verif);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Update verifikasi pickup (misal update status atau foto, oleh petugas/admin)
exports.updatePickupVerification = async (req, res) => {
  try {
    const updateData = { ...req.body };
    if (req.file) updateData.foto_bukti = req.file.path;

    const verif = await PickupVerification.findByIdAndUpdate(
      req.params.id,
      { $set: updateData },
      { new: true, runValidators: true }
    );
    if (!verif) return res.status(404).json({ message: 'Verifikasi pickup tidak ditemukan' });
    res.json({ message: 'Data verifikasi pickup diupdate', pickupVerification: verif });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Hapus data verifikasi pickup (admin only)
exports.deletePickupVerification = async (req, res) => {
  try {
    const verif = await PickupVerification.findByIdAndDelete(req.params.id);
    if (!verif) return res.status(404).json({ message: 'Data verifikasi pickup tidak ditemukan' });

    // Hapus file foto di server jika ada
    if (verif.foto_bukti && fs.existsSync(verif.foto_bukti)) {
      fs.unlinkSync(verif.foto_bukti);
    }
    res.json({ message: 'Data verifikasi pickup dihapus' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
