const Notification = require('../models/Notification');
const User = require('../models/User');

// Kirim notifikasi (umum, bisa dipakai admin/otomatis sistem)
exports.createNotification = async (req, res) => {
  try {
    const { user, tipe, judul, pesan, dataTerkait } = req.body;

    // Cek user penerima
    const targetUser = await User.findById(user);
    if (!targetUser) return res.status(404).json({ message: 'User penerima tidak ditemukan.' });

    const notification = new Notification({
      user,
      tipe,
      judul,
      pesan,
      dataTerkait
    });
    await notification.save();
    res.status(201).json({ message: 'Notifikasi berhasil dibuat', notification });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Ambil semua notifikasi untuk user login (inbox user)
exports.getMyNotifications = async (req, res) => {
  try {
    const notifs = await Notification.find({ user: req.user.userId })
      .sort({ createdAt: -1 });
    res.json(notifs);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tandai notifikasi sudah dibaca
exports.markAsRead = async (req, res) => {
  try {
    const notif = await Notification.findOneAndUpdate(
      { _id: req.params.id, user: req.user.userId },
      { isRead: true },
      { new: true }
    );
    if (!notif) return res.status(404).json({ message: 'Notifikasi tidak ditemukan' });
    res.json({ message: 'Notifikasi ditandai sudah dibaca', notification: notif });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// (Admin) Ambil semua notifikasi sistem
exports.getAllNotifications = async (req, res) => {
  try {
    const notifs = await Notification.find()
      .populate('user', 'nama email peran')
      .sort({ createdAt: -1 });
    res.json(notifs);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Hapus notifikasi (user bisa hapus sendiri, admin bisa hapus apapun)
exports.deleteNotification = async (req, res) => {
  try {
    const notif = await Notification.findOneAndDelete({
      _id: req.params.id,
      // Jika admin, bisa hapus apa saja; jika user, hanya milik sendiri
      ...(req.user.peran !== 'admin' && { user: req.user.userId })
    });
    if (!notif) return res.status(404).json({ message: 'Notifikasi tidak ditemukan' });
    res.json({ message: 'Notifikasi berhasil dihapus' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
