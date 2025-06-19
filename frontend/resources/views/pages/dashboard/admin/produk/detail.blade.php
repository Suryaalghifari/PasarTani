@extends('layouts.dashboard.main')
@section('title', 'Detail Produk')

@section('content')
<h2>Detail Produk</h2>
<div class="card">
    <div class="card-body">
        <div>
            <img src="{{ 'http://localhost:5000/' . ltrim($produk['foto'],'/') }}" style="width:120px;">
        </div>
        <h4>{{ $produk['nama'] }}</h4>
        <p>Kategori: {{ ucfirst($produk['kategori']) }}</p>
        <p>Harga: Rp {{ number_format($produk['harga'],0,',','.') }}</p>
        <p>Stok: {{ $produk['stok'] }}</p>
        <p>Deskripsi: {{ $produk['deskripsi'] }}</p>
        <hr>
        <h5>Data Petani</h5>
        <p>Nama: {{ $produk['id_petani']['nama'] ?? '-' }}</p>
        <p>Email: {{ $produk['id_petani']['email'] ?? '-' }}</p>
        <form action="{{ route('admin.produk.action', $produk['_id']) }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="status" value="tersedia">
            <button type="submit" class="btn btn-success">Setujui</button>
        </form>
        <form action="{{ route('admin.produk.action', $produk['_id']) }}" method="POST" class="mt-2">
            @csrf
            <input type="hidden" name="status" value="ditolak">
            <button type="submit" class="btn btn-danger">Tolak</button>
        </form>
    </div>
</div>
@endsection
