@extends('layouts.dashboard.main')
@section('title', 'Detail Logistik')

@section('content')
<div class="container py-4">
    <h2>Detail Pengiriman</h2>
    @if($logistik)
        <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Order ID:</strong> {{ $logistik['id_order']['_id'] ?? '-' }}</li>
            <li class="list-group-item"><strong>Petani:</strong> {{ $logistik['id_petani']['nama'] ?? '-' }}</li>
            <li class="list-group-item"><strong>Pickup Point:</strong>
                {{ $logistik['id_titik_ambil']['nama'] ?? '-' }},
                {{ $logistik['id_titik_ambil']['alamat'] ?? '-' }}
            </li>
            <li class="list-group-item"><strong>Jadwal Kirim:</strong>
                {{ !empty($logistik['jadwal_pengiriman']) ? date('d-m-Y H:i', strtotime($logistik['jadwal_pengiriman'])) : '-' }}
            </li>
            <li class="list-group-item"><strong>Status Pengiriman:</strong> {{ strtoupper($logistik['status_pengiriman']) }}</li>
            <li class="list-group-item"><strong>Catatan:</strong> {{ $logistik['catatan'] ?? '-' }}</li>
        </ul>
        <!-- Tombol aksi untuk update status bisa ditambahkan di sini -->
        {{-- <form method="POST" action="...">
            ...
        </form> --}}
    @else
        <div class="alert alert-danger">Data logistik tidak ditemukan.</div>
    @endif
    <a href="{{ route('admin.logistik') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
