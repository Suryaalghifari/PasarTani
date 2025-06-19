@extends('layouts.home.app')
@section('title', 'Checkout')
@section('content')

<div class="container py-4">
    <h2>Checkout</h2>
    @if($cart && count($cart['items']) > 0)
    <form action="{{ route('checkout.submit') }}" method="POST">
        @csrf
        <div class="mb-2">
            <label for="alamat_pengiriman">Alamat Pengiriman</label>
            <input type="text" class="form-control" name="alamat_pengiriman" required>
        </div>
        <div class="mb-2">
            <label for="no_hp">No HP</label>
            <input type="text" class="form-control" name="no_hp" required>
        </div>
        <div class="mb-2">
            <label for="id_titik_ambil">Pilih Titik Ambil</label>
            <select name="id_titik_ambil" class="form-control" required>
                <option value="">--Pilih Pickup Point--</option>
                @foreach($pickupPoints as $p)
                    <option value="{{ $p['_id'] }}">{{ $p['nama'] }} - {{ $p['alamat'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label for="metode_pembayaran">Metode Pembayaran</label>
            <select name="metode_pembayaran" required>
    <option value="">--Pilih--</option>
    <option value="cod">Bayar di Tempat (COD)</option>
    <option value="transfer">Transfer Bank</option>
    <option value="qris">QRIS</option>
    <option value="gopay">Gopay</option>
    <option value="ovo">OVO</option>
</select>
        </div>
        <div class="mb-2">
            <label for="catatan">Catatan (Opsional)</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Checkout</button>
    </form>
    @else
        <div class="alert alert-info">Keranjang kosong!</div>
    @endif
</div>
@endsection
