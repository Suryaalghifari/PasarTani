const User = require("../models/User");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");

require("dotenv").config();

// Register user baru
exports.register = async (req, res) => {
  try {
    const { nama, email, password, peran, alamat, no_hp } = req.body;

    // Cek user sudah ada
    const userExist = await User.findOne({ email });
    if (userExist) {
      return res.status(400).json({ message: "Email sudah terdaftar" });
    }

    // Hash password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Buat user baru
    const newUser = new User({
      nama,
      email,
      password: hashedPassword,
      peran,
      alamat,
      no_hp,
      // foto default atau req.body.foto jika pakai upload profil
    });

    await newUser.save();

    res.status(201).json({
      message: "Registrasi berhasil",
      user: {
        _id: newUser._id,
        nama: newUser.nama,
        email: newUser.email,
        peran: newUser.peran,
      },
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Login user
exports.login = async (req, res) => {
  try {
    const { email, password } = req.body;

    // Cari user berdasarkan email
    const user = await User.findOne({ email });
    if (!user)
      return res.status(400).json({ message: "Email belum terdaftar" });

    // Bandingkan password
    const isMatch = await bcrypt.compare(password, user.password);
    if (!isMatch) return res.status(400).json({ message: "Password salah" });

    // Buat token JWT
    const payload = {
      userId: user._id,
      peran: user.peran,
    };
    const token = jwt.sign(payload, process.env.JWT_SECRET, {
      expiresIn: "7d",
    });

    res.status(200).json({
      message: "Login berhasil",
      token,
      user: {
        _id: user._id,
        nama: user.nama,
        email: user.email,
        peran: user.peran,
      },
    });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// (Opsional) Cek validitas token / ambil data profile user dari token
exports.profile = async (req, res) => {
  try {
    // User sudah di-inject dari middleware auth
    const user = await User.findById(req.user.userId).select("-password");
    if (!user) return res.status(404).json({ message: "User tidak ditemukan" });

    res.json(user);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
