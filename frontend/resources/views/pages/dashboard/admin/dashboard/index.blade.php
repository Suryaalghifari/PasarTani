@extends('layouts.dashboard.main')
@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-2">
   <div class="ms-3 mb-4">
        <h3 class="mb-0 h4 font-weight-bolder">Dashboard Admin</h3>
        <p class="mb-1">
            Selamat datang,
            <span class="fw-bold text-primary">
                Admin
            </span>
            <span class="fw-bold">
                {{ session('user.nama') ?? '-' }}
            </span>
            ! Pantau aktivitas dan data sistem Anda.
        </p>
        <p class="mb-0 text-muted">Ringkasan statistik aplikasi.</p>
    </div>

    <div class="row">
        <!-- Total User -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Users</p>
                            <h4 class="mb-0">{{ $data['totalUsers'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">group</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Semua user teregistrasi</p>
                </div>
            </div>
        </div>

        <!-- Total Petani -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Petani</p>
                            <h4 class="mb-0">{{ $data['totalPetani'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-success shadow-success text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">eco</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Akun petani terdaftar</p>
                </div>
            </div>
        </div>

        <!-- Total Konsumen -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Konsumen</p>
                            <h4 class="mb-0">{{ $data['totalKonsumen'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-info shadow-info text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">person</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Konsumen aktif di sistem</p>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Produk</p>
                            <h4 class="mb-0">{{ $data['totalProduk'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-primary shadow-primary text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">inventory_2</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Produk aktif di sistem</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Total Order -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Order</p>
                            <h4 class="mb-0">{{ $data['totalOrder'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-warning shadow-warning text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">shopping_bag</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Total pesanan masuk</p>
                </div>
            </div>
        </div>

        <!-- Total Pickup Point -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Pickup Point</p>
                            <h4 class="mb-0">{{ $data['totalPickupPoint'] ?? 0 }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-secondary shadow-secondary text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">location_on</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Total titik pickup aktif</p>
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
                    <p class="mb-0 text-sm">Total pendapatan sistem</p>
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
                        <div class="icon icon-md icon-shape bg-gradient-danger shadow-danger text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">notifications_active</i>
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
