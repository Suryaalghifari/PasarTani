@extends('layouts.home.app')

@section('title', 'Keranjang Belanja')

@section('content')
<!-- Main Content -->
<main class="main-content py-5">
    <div class="container">
        <div class="cart-layout">
            <!-- Cart Items Section -->
            <div class="cart-section">
                <div class="cart-card">
                    <h1 class="cart-title">Keranjang Belanja</h1>

                    @if($cart && count($cart['items']) > 0)
                        <div class="cart-items">
                            @foreach($cart['items'] as $item)
                            <div class="cart-item" data-produk="{{ $item['produk'] }}">
                                <img src="" alt="{{ $item['nama_produk'] ?? '-' }}" class="product-image produk-img-{{ $item['produk'] }}">
                                <div class="product-details">
                                    <div class="product-name">{{ $item['nama_produk'] ?? '-' }}</div>
                                    <div class="product-price text-muted">
                                        Rp{{ number_format($item['harga'] ?? 0,0,',','.') }} x {{ $item['jumlah'] ?? 1 }} {{ $item['satuan'] ?? '' }}
                                    </div>
                                </div>
                                <div class="item-total">
                                    <div class="item-total-price">
                                        Rp{{ number_format($item['subtotal'] ?? 0,0,',','.') }}
                                    </div>
                                </div>
                                <button class="remove-btn" data-id="{{ $item['produk'] }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-cart text-center">
                            <i class="fas fa-shopping-cart empty-cart-icon"></i>
                            <p class="empty-cart-text">Keranjang belanja Anda kosong</p>
                            <a href="{{ route('home') }}" class="btn btn-outline-success mt-3">Mulai Berbelanja</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="summary-section">
                <div class="summary-card">
                    <h2 class="summary-title">Ringkasan Pesanan</h2>

                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal ({{ count($cart['items'] ?? []) }} item)</span>
                            <span>Rp{{ number_format($cart['total_harga'] ?? 0,0,',','.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Ongkos Kirim</span>
                            <span class="free-shipping">GRATIS</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total-row">
                            <span>Total</span>
                            <span class="total-amount">Rp{{ number_format($cart['total_harga'] ?? 0,0,',','.') }}</span>
                        </div>
                    </div>

                    @if($cart && count($cart['items']) > 0)
                    <a href="{{ route('checkout') }}" class="btn btn-checkout w-100 mt-3">Lanjut ke Checkout</a>
                    @else
                    <button class="btn btn-checkout w-100 mt-3" disabled>Lanjut ke Checkout</button>
                    @endif

                    <a href="{{ route('home') }}" class="btn btn-outline w-100 mt-2">Lanjut Berbelanja</a>

                    <div class="security-info mt-3 text-muted text-center">
                        <i class="fas fa-lock"></i>
                        <span>Pembayaran aman dan terpercaya</span>
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
    // Fetch gambar produk untuk tiap item di cart
    document.querySelectorAll('.cart-item').forEach(function(cartItem) {
        var produkId = cartItem.getAttribute('data-produk');
        if (produkId) {
            fetch('http://192.168.100.41:5000/api/products/' + produkId)
            .then(res => res.json())
            .then(data => {
                if (data && data.foto) {
                    var imgEl = cartItem.querySelector('.produk-img-' + produkId);
                    if (imgEl) {
                        imgEl.src = 'http://192.168.100.41:5000/' + data.foto.replace(/^\//, '');
                    }
                }
            });
        }
    });

    // Handler untuk hapus produk dari keranjang
    document.querySelectorAll('.remove-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var produkId = btn.getAttribute('data-id');
            fetch("{{ route('keranjang.hapus') }}", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ produk: produkId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.cart) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal hapus produk');
                }
            })
            .catch(() => alert('Gagal koneksi ke server!'));
        });
    });
});
</script>
@endpush
