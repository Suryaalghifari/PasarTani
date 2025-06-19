const User = require("../models/User");
const bcrypt = require("bcryptjs");

// Get daftar semua user (hanya admin)
exports.getAllUsers = async (req, res) => {
  try {
    const users = await User.find().select("-password");
    res.json(users);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tambah user baru (admin)
// Tambah user baru oleh admin
exports.createUserByAdmin = async (req, res) => {
  try {
    const { nama, email, password, peran, alamat, no_hp } = req.body;

    if (!nama || !email || !password || !peran) {
      return res.status(400).json({ message: "Field wajib belum diisi!" });
    }

    const userExist = await User.findOne({ email });
    if (userExist) {
      return res.status(400).json({ message: "Email sudah terdaftar" });
    }

    const hashedPassword = await bcrypt.hash(password, 10);

    const newUser = new User({
      nama,
      email,
      password: hashedPassword,
      peran,
      alamat,
      no_hp,
      foto: req.file ? `${peran}/${req.file.filename}` : undefined,
    });

    await newUser.save();

    // Kirim user tanpa password ke frontend
    const userNoPass = await User.findById(newUser._id).select("-password");
    res
      .status(201)
      .json({ message: "User berhasil ditambahkan", user: userNoPass });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

exports.updateUserByAdmin = async (req, res) => {
  try {
    const { nama, email, peran, alamat, no_hp, password } = req.body;
    const updateData = { nama, email, peran, alamat, no_hp };

    if (req.file) {
      updateData.foto = `${peran}/${req.file.filename}`;
    }

    if (password && password.length > 5) {
      updateData.password = await bcrypt.hash(password, 10);
    }

    const updatedUser = await User.findByIdAndUpdate(
      req.params.id,
      { $set: updateData },
      { new: true, runValidators: true }
    ).select("-password");

    if (!updatedUser) {
      return res.status(404).json({ message: "User tidak ditemukan" });
    }

    res.json({ message: "User berhasil diupdate", user: updatedUser });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get detail user by id (admin atau user sendiri)
exports.getUserById = async (req, res) => {
  try {
    const user = await User.findById(req.params.id).select("-password");
    if (!user) return res.status(404).json({ message: "User tidak ditemukan" });
    res.json(user);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Update user by id (admin atau user sendiri)
exports.updateUser = async (req, res) => {
  try {
    const { nama, alamat, no_hp, password } = req.body;
    const updateData = { nama, alamat, no_hp };

    // Handle upload file
    if (req.file) {
      // Simpan path relatif, misal: 'petani/namafile.png'
      updateData.foto = `${req.user.peran}/${req.file.filename}`;
    }

    // Jika user ganti password
    if (password && password.length > 5) {
      updateData.password = await bcrypt.hash(password, 10);
    }

    const updatedUser = await User.findByIdAndUpdate(
      req.params.id,
      { $set: updateData },
      { new: true, runValidators: true }
    ).select("-password");

    if (!updatedUser)
      return res.status(404).json({ message: "User tidak ditemukan" });

    res.json({ message: "User berhasil diupdate", user: updatedUser });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Hapus user (hanya admin)
exports.deleteUser = async (req, res) => {
  try {
    const deleted = await User.findByIdAndDelete(req.params.id);
    if (!deleted)
      return res.status(404).json({ message: "User tidak ditemukan" });
    res.json({ message: "User berhasil dihapus" });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get profile sendiri (user login)
exports.getMyProfile = async (req, res) => {
  try {
    const user = await User.findById(req.user.userId).select("-password");
    if (!user) return res.status(404).json({ message: "User tidak ditemukan" });
    res.json(user);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Update profile sendiri (user login)
exports.updateMyProfile = async (req, res) => {
  try {
    const { nama, alamat, no_hp, password } = req.body;
    const updateData = { nama, alamat, no_hp };

    if (req.file) {
      // Simpan path relatif, misal: 'petani/namafile.png'
      updateData.foto = `${req.user.peran}/${req.file.filename}`;
    }

    if (password && password.length > 5) {
      updateData.password = await bcrypt.hash(password, 10);
    }
    const updatedUser = await User.findByIdAndUpdate(
      req.user.userId,
      { $set: updateData },
      { new: true, runValidators: true }
    ).select("-password");
    if (!updatedUser)
      return res.status(404).json({ message: "User tidak ditemukan" });
    res.json({ message: "Profile berhasil diupdate", user: updatedUser });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
