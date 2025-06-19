@extends('layouts.dashboard.main')
@section('title', 'Dashboard Petani')

@section('content')
<div class="container-fluid py-2">
   <div class="ms-3 mb-4">
        <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
        <p class="mb-1">
            Selamat datang, 
            <span class="fw-bold text-primary">
                {{ ucfirst($role) }}
            </span>
            <span class="fw-bold">
                {{ session('user.nama') ?? '-' }}
            </span>
            ! Semoga hari Anda menyenangkan & aktivitas pertanian lancar.
        </p>
        <p class="mb-0 text-muted">Ringkasan aktivitas usaha Anda.</p>
</div>

    <div class="row">
        <!-- Total Produk -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Produk Saya</p>
                            <h4 class="mb-0">{{ $data['totalProdukSaya'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-primary shadow-primary text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">shopping_cart</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Semua produk milik Anda</p>
                </div>
            </div>
        </div>

        <!-- Total Order Produk -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Order Produk Saya</p>
                            <h4 class="mb-0">{{ $data['totalOrderProdukSaya'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-info shadow-info text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">receipt_long</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Order dari pembeli</p>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Pendapatan</p>
                            <h4 class="mb-0">Rp {{ number_format($data['totalPendapatan'] ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-success shadow-success text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">payments</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Akumulasi penjualan produk</p>
                </div>
            </div>
        </div>

        <!-- Notifikasi Belum Dibaca -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Notifikasi Belum Dibaca</p>
                            <h4 class="mb-0">{{ $data['unreadNotif'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-warning shadow-warning text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">notifications</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Notifikasi dari sistem</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
