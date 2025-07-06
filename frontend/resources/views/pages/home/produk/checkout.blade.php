@extends('layouts.home.app')
@section('title', 'Checkout')
@section('content')

<!-- Header & Breadcrumb (Opsional, sudah ada di layout) -->
<div class="breadcrumb">
    <div class="container">
        <nav class="breadcrumb-nav">
            <a href="{{ route('keranjang') }}" class="breadcrumb-link">
                <i class="fas fa-shopping-cart"></i>
                Keranjang
            </a>
            <i class="fas fa-chevron-right breadcrumb-separator"></i>
            <span class="breadcrumb-current">
                <i class="fas fa-credit-card"></i>
                Checkout
            </span>
        </nav>
    </div>
</div>

<main class="main-content">
    <div class="container">
        <div class="checkout-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Checkout Form -->
            <div class="checkout-form-section">
                <div class="form-card">
                    <h1 class="checkout-title">
                        <i class="fas fa-credit-card"></i>
                        Checkout
                    </h1>

                    @if($cart && count($cart['items']) > 0)
                    <form action="{{ route('checkout.submit') }}" method="POST" id="checkoutForm" class="checkout-form">
                        @csrf
                        <!-- Informasi Pengiriman -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <i class="fas fa-truck"></i>
                                Informasi Pengiriman
                            </h2>
                            <div class="form-group">
                                <label for="alamat_pengiriman" class="form-label">Alamat Pengiriman <span class="required">*</span></label>
                                <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-input form-textarea" rows="3" required placeholder="Masukkan alamat lengkap pengiriman..."></textarea>
                            </div>
                            <div class="form-row" style="display: flex; gap:1rem;">
                                <div class="form-group" style="flex:1;">
                                    <label for="no_hp" class="form-label">No HP <span class="required">*</span></label>
                                    <input type="tel" name="no_hp" class="form-input" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label for="id_titik_ambil" class="form-label">Pilih Titik Ambil <span class="required">*</span></label>
                                    <select name="id_titik_ambil" class="form-select" required>
                                        <option value="">--Pilih Pickup Point--</option>
                                        @foreach($pickupPoints as $p)
                                            <option value="{{ $p['_id'] }}">{{ $p['nama'] }} - {{ $p['alamat'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Metode Pembayaran -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <i class="fas fa-credit-card"></i>
                                Metode Pembayaran
                            </h2>
                            <div class="payment-methods">
                                <div class="payment-option">
                                    <input type="radio" id="cod" name="metode_pembayaran" value="cod" class="payment-radio" required>
                                    <label for="cod" class="payment-label">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Bayar di Tempat (COD)
                                    </label>
                                </div>
                                <div class="payment-option">
                                    <input type="radio" id="transfer" name="metode_pembayaran" value="transfer" class="payment-radio">
                                    <label for="transfer" class="payment-label">
                                        <i class="fas fa-university"></i>
                                        Transfer Bank
                                    </label>
                                </div>
                                <div class="payment-option">
                                    <input type="radio" id="qris" name="metode_pembayaran" value="qris" class="payment-radio">
                                    <label for="qris" class="payment-label">
                                        <i class="fas fa-qrcode"></i>
                                        QRIS
                                    </label>
                                </div>
                                <div class="payment-option">
                                    <input type="radio" id="gopay" name="metode_pembayaran" value="gopay" class="payment-radio">
                                    <label for="gopay" class="payment-label">
                                        <i class="fab fa-google-pay"></i>
                                        Gopay
                                    </label>
                                </div>
                                <div class="payment-option">
                                    <input type="radio" id="ovo" name="metode_pembayaran" value="ovo" class="payment-radio">
                                    <label for="ovo" class="payment-label">
                                        <i class="fab fa-cc-visa"></i>
                                        OVO
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Catatan -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Catatan Tambahan
                            </h2>
                            <div class="form-group">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea name="catatan" id="catatan" class="form-input form-textarea" rows="2" placeholder="Catatan untuk pesanan..."></textarea>
                            </div>
                        </div>
                        <!-- Action -->
                        <div class="checkout-actions" style="display:flex;gap:1rem;">
                            <a href="{{ route('keranjang') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                            </a>
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-check"></i> Checkout
                            </button>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-info">Keranjang kosong!</div>
                    @endif
                </div>
            </div>
            <!-- Order Summary -->
            <div class="order-summary-section">
                <div class="summary-card">
                    <h2 class="summary-title">
                        <i class="fas fa-receipt"></i>
                        Ringkasan Pesanan
                    </h2>
                    <!-- Order Items -->
                    <div class="order-items" id="orderItems">
                        @if($cart && count($cart['items']) > 0)
                            @foreach($cart['items'] as $item)
                                <div class="order-item" style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                                    <img src="" data-produk="{{ $item['produk'] }}" alt="{{ $item['nama_produk'] }}" class="product-image order-item-img-{{ $item['produk'] }}" style="width:48px;height:48px;border-radius:8px;object-fit:cover;">
                                    <div>
                                        <div class="product-name" style="font-weight:600;">{{ $item['nama_produk'] }}</div>
                                        <div style="color:#6b7280;font-size:0.9em;">{{ $item['jumlah'] }} x Rp{{ number_format($item['harga'],0,',','.') }}</div>
                                    </div>
                                    <div style="margin-left:auto;font-weight:bold;">
                                        Rp{{ number_format($item['subtotal'],0,',','.') }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- Order Details -->
                    <div class="order-details">
                        <div class="detail-row">
                            <span>Subtotal ({{ count($cart['items'] ?? []) }} item)</span>
                            <span id="subtotalAmount">Rp{{ number_format($cart['total_harga'] ?? 0,0,',','.') }}</span>
                        </div>
                        <div class="detail-row">
                            <span>Ongkos Kirim</span>
                            <span class="free-shipping">GRATIS</span>
                        </div>
                        <div class="detail-row">
                            <span>Biaya Admin</span>
                            <span>Rp0</span>
                        </div>
                        <div class="detail-divider"></div>
                        <div class="detail-row total-row">
                            <span>Total Pembayaran</span>
                            <span class="total-amount" id="totalAmount">Rp{{ number_format($cart['total_harga'] ?? 0,0,',','.') }}</span>
                        </div>
                    </div>
                    <div class="security-info mt-3 text-muted text-center" style="margin-top:1.5rem;">
                        <i class="fas fa-shield-alt"></i>
                        <span>Transaksi aman dan data terlindungi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Untuk setiap item di ringkasan pesanan, ambil gambar produk dari API
    document.querySelectorAll('.order-item img[data-produk]').forEach(function(img) {
        var produkId = img.getAttribute('data-produk');
        if (produkId) {
            fetch('http://192.168.100.41:5000/api/products/' + produkId)
                .then(res => res.json())
                .then(data => {
                    if (data && data.foto) {
                        img.src = 'http://192.168.100.41:5000/' + data.foto.replace(/^\//, '');
                    }
                });
        }
    });
});
</script>
@endpush
