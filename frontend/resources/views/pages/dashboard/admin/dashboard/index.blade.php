@extends('layouts.dashboard.main')

@section('content')
    <h1>Dashboard Admin</h1>
    <ul>
        <li>Total Users: {{ $data['totalUsers'] ?? '0' }}</li>
        <li>Total Petani: {{ $data['totalPetani'] ?? '0' }}</li>
        <li>Total Konsumen: {{ $data['totalKonsumen'] ?? '0' }}</li>
        <li>Total Produk: {{ $data['totalProduk'] ?? '0' }}</li>
        <li>Total Order: {{ $data['totalOrder'] ?? '0' }}</li>
        <li>Total Pickup Point: {{ $data['totalPickupPoint'] ?? '0' }}</li>
        <li>Total Pendapatan: Rp {{ number_format($data['totalPendapatan'] ?? 0, 0, ',', '.') }}</li>
        <li>Notifikasi Belum Dibaca: {{ $data['unreadNotif'] ?? '0' }}</li>
    </ul>
@endsection
