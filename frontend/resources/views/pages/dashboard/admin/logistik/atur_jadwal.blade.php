@extends('layouts.dashboard.main')
@section('title', 'Atur Jadwal Pengiriman')

@section('content')
<div class="container py-4">
    <h3>Atur Jadwal Pengiriman</h3>
    <form action="{{ route('admin.logistik.simpan_jadwal', $order['_id']) }}" method="POST">
        @csrf
        <div class="mb-2">
    <label for="id_petani">Petani</label>
    <input type="text" class="form-control" value="{{ $order['items'][0]['produk']['id_petani']['nama'] ?? '-' }}" readonly>
    <input type="hidden" name="id_petani" value="{{ $order['items'][0]['produk']['id_petani']['_id'] ?? '' }}">
</div>
        <div class="mb-2">
            <label for="id_titik_ambil">Pickup Point</label>
            <input type="text" class="form-control" value="{{ $order['id_titik_ambil']['nama'] ?? '-' }}" readonly>
            <input type="hidden" name="id_titik_ambil" value="{{ $order['id_titik_ambil']['_id'] ?? '' }}">
        </div>
        <div class="mb-2">
            <label for="jadwal_pengambilan">Jadwal Pengambilan</label>
            <input type="datetime-local" name="jadwal_pengambilan" class="form-control" required>
        </div>
        <div class="mb-2">
            <label for="jadwal_pengiriman">Jadwal Pengiriman</label>
            <input type="datetime-local" name="jadwal_pengiriman" class="form-control" required>
        </div>
        <div class="mb-2">
            <label for="kurir">Kurir/Petugas Pengirim</label>
            <input type="text" name="kurir" class="form-control" required>
        </div>
        <div class="mb-2">
            <label for="catatan">Catatan (opsional)</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Simpan Jadwal</button>
    </form>
</div>
@endsection
