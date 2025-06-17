const nodemailer = require('nodemailer');
require('dotenv').config();

exports.sendEmail = async ({ to, subject, text, html }) => {
  const transporter = nodemailer.createTransport({
    service: 'gmail', // atau 'smtp', dsb
    auth: {
      user: process.env.EMAIL_USER,
      pass: process.env.EMAIL_PASS
    }
  });

  return transporter.sendMail({
    from: `"Pasar Tani" <${process.env.EMAIL_USER}>`,
    to,
    subject,
    text,
    html
  });
};
