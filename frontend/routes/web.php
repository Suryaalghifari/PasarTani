<?php

use Illuminate\Support\Facades\Route;

// ========================= AUTH & HOME =========================
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\PaymentController;
use App\Http\Controllers\Home\OrderController;

// ========================= ADMIN =========================
use App\Http\Controllers\dashboard\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\dashboard\Admin\Produk\ProdukAdminController;
use App\Http\Controllers\dashboard\Admin\PickupPointController;
use App\Http\Controllers\dashboard\Admin\VerifikasiPesananController;
use App\Http\Controllers\dashboard\Admin\LogistikController;
use App\Http\Controllers\dashboard\Admin\UserAdminController;

// ========================= PETANI =========================
use App\Http\Controllers\dashboard\Petani\DashboardController as PetaniDashboardController;
use App\Http\Controllers\dashboard\Petani\ProfileController as PetaniProfileController;
use App\Http\Controllers\dashboard\Petani\Produk\ProdukController;
use App\Http\Controllers\dashboard\Petani\Produk\PetaniOrderController;

// ========================= PETUGAS =========================
use App\Http\Controllers\dashboard\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\dashboard\Petugas\PickupVerificationController as PetugasPickupVerificationController;

// ==========================
//         ROUTES
// ==========================

// --------- UMUM (Public) ---------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.submit');
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'loginUser'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --------- UMUM: Keranjang & Checkout ---------
Route::get('/keranjang', [HomeController::class, 'cart'])->name('keranjang');
Route::post('/keranjang/tambah', [HomeController::class, 'tambahKeranjang'])->name('keranjang.tambah');
Route::delete('/keranjang/hapus', [HomeController::class, 'hapusKeranjang'])->name('keranjang.hapus');
Route::get('/checkout', [HomeController::class, 'checkoutForm'])->name('checkout');
Route::post('/checkout', [HomeController::class, 'prosesCheckout'])->name('checkout.submit');

Route::get('/payment/{id_checkout}', [PaymentController::class, 'show'])->name('payment.upload');
Route::post('/payment/{id_checkout}', [PaymentController::class, 'upload'])->name('payment.upload.submit');
Route::get('/pesanan-saya', [OrderController::class, 'index'])->name('pesanan.saya');

// ====================== ADMIN ======================
Route::middleware(['sessionuser', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('user', [UserAdminController::class, 'index'])->name('admin.user.index');
    Route::get('user/create', [UserAdminController::class, 'create'])->name('admin.user.create');
    Route::post('user', [UserAdminController::class, 'store'])->name('admin.user.store');
    Route::get('user/{id}/edit', [UserAdminController::class, 'edit'])->name('admin.user.edit');
    Route::put('user/{id}', [UserAdminController::class, 'update'])->name('admin.user.update');
    Route::delete('user/{id}', [UserAdminController::class, 'destroy'])->name('admin.user.destroy');



    // Produk
    Route::prefix('produk')->group(function () {
        Route::get('verifikasi', [ProdukAdminController::class, 'verifikasiIndex'])->name('admin.produk.verifikasi');
        Route::get('{id}', [ProdukAdminController::class, 'show'])->name('admin.produk.detail');
        Route::post('{id}/verifikasi', [ProdukAdminController::class, 'verifikasi'])->name('admin.produk.action');
    });

    // Pickup Point
    Route::prefix('pickup-point')->group(function () {
        Route::get('/', [PickupPointController::class, 'index'])->name('admin.pickup_point.index');
        Route::get('/create', [PickupPointController::class, 'create'])->name('admin.pickup_point.create');
        Route::post('/', [PickupPointController::class, 'store'])->name('admin.pickup_point.store');
        Route::get('/{id}/edit', [PickupPointController::class, 'edit'])->name('admin.pickup_point.edit');
        Route::put('/{id}', [PickupPointController::class, 'update'])->name('admin.pickup_point.update');
        Route::delete('/{id}', [PickupPointController::class, 'destroy'])->name('admin.pickup_point.destroy');
    });

    // Verifikasi Pesanan
    Route::get('/verifikasi-pesanan', [VerifikasiPesananController::class, 'index'])->name('admin.verifikasi_pesanan');
    Route::post('/verifikasi-pesanan/{id}', [VerifikasiPesananController::class, 'verif'])->name('admin.verifikasi_pesanan.aksi');

    // Logistik
    Route::get('logistik', [LogistikController::class, 'index'])->name('admin.logistik');
    Route::get('logistik/order/{id}', [LogistikController::class, 'show'])->name('admin.logistik.detail');
    Route::get('logistik/siap-diambil', [LogistikController::class, 'siapDiambil'])->name('admin.logistik.siap_diambil');
    Route::get('logistik/order/{id}/atur', [LogistikController::class, 'formAturJadwal'])->name('admin.logistik.atur_jadwal');
    Route::post('logistik/order/{id}/atur', [LogistikController::class, 'simpanJadwal'])->name('admin.logistik.simpan_jadwal');
});

// ====================== PETANI ======================
Route::middleware(['sessionuser', 'role:petani'])->prefix('petani')->group(function () {
    Route::get('dashboard', [PetaniDashboardController::class, 'index'])->name('petani.dashboard');
    // Profile
    Route::get('profile', [PetaniProfileController::class, 'edit'])->name('petani.profile.edit');
    Route::post('profile', [PetaniProfileController::class, 'update'])->name('petani.profile.update');
    // Produk Petani
    Route::prefix('produk')->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('petani.produk.index');
        Route::get('create', [ProdukController::class, 'create'])->name('petani.produk.create');
        Route::post('/', [ProdukController::class, 'store'])->name('petani.produk.store');
        Route::get('{id}/edit', [ProdukController::class, 'edit'])->name('petani.produk.edit');
        Route::put('{id}', [ProdukController::class, 'update'])->name('petani.produk.update');
        Route::delete('{id}', [ProdukController::class, 'destroy'])->name('petani.produk.destroy');
    });
    // Orders (Pesanan masuk ke petani)
    Route::get('orders', [PetaniOrderController::class, 'index'])->name('petani.orders');
    Route::post('orders/{id}/update', [PetaniOrderController::class, 'updateStatus'])->name('petani.order.update');
});

// ====================== PETUGAS ======================
Route::middleware(['sessionuser', 'role:petugas'])->prefix('petugas')->group(function () {
    // Dashboard utama petugas
    Route::get('dashboard', [PetugasDashboardController::class, 'index'])->name('petugas.dashboard');
    // Daftar logistik (lihat semua pengiriman)
    Route::get('logistik', [PetugasDashboardController::class, 'index'])->name('petugas.logistik');
    // Detail logistik order
    Route::get('logistik/order/{id}', [PetugasDashboardController::class, 'show'])->name('petugas.logistik.detail');
    // Update status logistik (pengiriman dsb)
    Route::post('logistik/order/{id}/update-status', [PetugasDashboardController::class, 'updateStatus'])->name('petugas.logistik.update_status');

    // Pickup Verification (verifikasi pengambilan oleh kurir/petugas)
    Route::get('pickup-verification/{id_order}', [PetugasPickupVerificationController::class, 'form'])->name('petugas.pickup_verification.form');
    Route::post('pickup-verification/{id_order}', [PetugasPickupVerificationController::class, 'upload'])->name('petugas.pickup_verification.upload');

    Route::get('profile', [\App\Http\Controllers\dashboard\Petugas\ProfileController::class, 'edit'])->name('petugas.profile.edit');
    Route::put('profile/{id}', [\App\Http\Controllers\dashboard\Petugas\ProfileController::class, 'update'])->name('petugas.profile.update');

});

