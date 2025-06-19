@extends('layouts.home.app')
@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height:60vh;">
    <div class="col-lg-6 col-md-8 col-12">
        <div class="bg-white p-4 rounded-4 shadow-sm">

            <h2 class="mb-4">Upload Bukti Pembayaran</h2>
            
            {{-- Notifikasi error --}}
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            {{-- RINGKASAN PESANAN --}}
            <div class="card mb-4">
                <div class="card-header bg-light">Ringkasan Pesanan</div>
                <ul class="list-group list-group-flush">
                    @foreach($checkout['cart_items'] as $item)
                        <li class="list-group-item">
                            <strong>{{ $item['nama_produk'] ?? '-' }}</strong> x{{ $item['jumlah'] ?? 1 }}
                            &mdash; {{ number_format($item['subtotal'] ?? 0) }}
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Total</strong>
                        <strong>{{ number_format($checkout['total_harga'] ?? 0) }}</strong>
                    </li>
                    <li class="list-group-item">
                        <strong>Metode Pembayaran:</strong> {{ strtoupper($checkout['metode_pembayaran']) }}
                    </li>
                    <li class="list-group-item">
                        <strong>Pickup Point:</strong>
{{ $checkout['id_titik_ambil']['nama'] ?? '-' }}
{{ isset($checkout['id_titik_ambil']['alamat']) ? ' - '.$checkout['id_titik_ambil']['alamat'] : '' }}

                    </li>
                    <li class="list-group-item">
                        <strong>Alamat Pengiriman:</strong> {{ $checkout['alamat_pengiriman'] ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <strong>No HP:</strong> {{ $checkout['no_hp'] ?? '-' }}
                    </li>
                    @if(!empty($checkout['catatan']))
                    <li class="list-group-item">
                        <strong>Catatan Checkout:</strong> {{ $checkout['catatan'] }}
                    </li>
                    @endif
                </ul>
            </div>

            {{-- FORM UPLOAD BUKTI --}}
            <form action="{{ route('payment.upload.submit', $checkout['_id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label">
                        Upload Bukti Pembayaran <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*,application/pdf" required>
                </div>
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2"></textarea>
                </div>
                <button class="btn btn-success px-4" type="submit">Upload</button>
            </form>

            <div class="mt-3 small text-muted">
                <strong>Note:</strong> Silakan upload bukti transfer sesuai instruksi pembayaran.<br>
                <strong>Nominal:</strong> {{ number_format($checkout['total_harga'] ?? 0) }}, 
                <strong>Metode:</strong> {{ strtoupper($checkout['metode_pembayaran']) }}
            </div>
        </div>
    </div>
</div>
@endsection
