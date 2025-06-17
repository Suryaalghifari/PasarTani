const PDFDocument = require('pdfkit');
const fs = require('fs');

exports.generateSalesReportPDF = (reportData, outputPath) => {
  return new Promise((resolve, reject) => {
    try {
      const doc = new PDFDocument();
      doc.pipe(fs.createWriteStream(outputPath));
      doc.fontSize(18).text('Laporan Penjualan Pasar Tani Digital', { align: 'center' });
      doc.moveDown();
      // Tambah summary data
      doc.fontSize(12).text(`Periode: ${reportData.periode}`);
      doc.text(`Total Pendapatan: Rp${reportData.total_pendapatan}`);
      doc.text(`Total Transaksi: ${reportData.total_transaksi}`);
      doc.moveDown();
      // Tambah detail produk
      doc.fontSize(14).text('Ringkasan Produk Terjual:');
      Object.keys(reportData.data.produkSummary).forEach(nama_produk => {
        const prod = reportData.data.produkSummary[nama_produk];
        doc.fontSize(12).text(`${nama_produk}: ${prod.jumlah} pcs | Rp${prod.pendapatan}`);
      });
      doc.end();
      resolve(outputPath);
    } catch (err) {
      reject(err);
    }
  });
};
