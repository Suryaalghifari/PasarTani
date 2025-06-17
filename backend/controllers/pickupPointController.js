const PickupPoint = require('../models/PickupPoint');

// Tambah pickup point (admin)
exports.createPickupPoint = async (req, res) => {
  try {
    const { nama, alamat, jam_operasional, kontak, status, keterangan } = req.body;
    const pickupPoint = new PickupPoint({
      nama,
      alamat,
      jam_operasional,
      kontak,
      status: status || 'aktif',
      keterangan
    });
    await pickupPoint.save();
    res.status(201).json({ message: 'Titik pengambilan berhasil ditambah', pickupPoint });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Ambil semua pickup point
exports.getAllPickupPoints = async (req, res) => {
  try {
    const pickupPoints = await PickupPoint.find().sort({ createdAt: -1 });
    res.json(pickupPoints);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Ambil pickup point aktif saja (untuk frontend konsumen)
exports.getActivePickupPoints = async (req, res) => {
  try {
    const pickupPoints = await PickupPoint.find({ status: 'aktif' }).sort({ nama: 1 });
    res.json(pickupPoints);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Ambil detail pickup point by id
exports.getPickupPointById = async (req, res) => {
  try {
    const pickupPoint = await PickupPoint.findById(req.params.id);
    if (!pickupPoint) return res.status(404).json({ message: 'Titik pengambilan tidak ditemukan' });
    res.json(pickupPoint);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Update pickup point by id (admin)
exports.updatePickupPoint = async (req, res) => {
  try {
    const { nama, alamat, jam_operasional, kontak, status, keterangan } = req.body;
    const pickupPoint = await PickupPoint.findByIdAndUpdate(
      req.params.id,
      { $set: { nama, alamat, jam_operasional, kontak, status, keterangan } },
      { new: true, runValidators: true }
    );
    if (!pickupPoint) return res.status(404).json({ message: 'Titik pengambilan tidak ditemukan' });
    res.json({ message: 'Titik pengambilan berhasil diupdate', pickupPoint });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Hapus pickup point (admin)
exports.deletePickupPoint = async (req, res) => {
  try {
    const pickupPoint = await PickupPoint.findByIdAndDelete(req.params.id);
    if (!pickupPoint) return res.status(404).json({ message: 'Titik pengambilan tidak ditemukan' });
    res.json({ message: 'Titik pengambilan berhasil dihapus' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
