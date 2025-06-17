@extends('layouts.dashboard.main')

@section('content')
    <h1>Dashboard Petani</h1>
    <ul>
        <li>Total Produk Saya: {{ $data['totalProdukSaya'] ?? 0 }}</li>
        <li>Total Order Produk Saya: {{ $data['totalOrderProdukSaya'] ?? 0 }}</li>
        <li>Total Pendapatan: Rp {{ number_format($data['totalPendapatan'] ?? 0, 0, ',', '.') }}</li>
        <li>Notifikasi Belum Dibaca: {{ $data['unreadNotif'] ?? 0 }}</li>
    </ul>
@endsection
