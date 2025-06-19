<div class="products-grid">
    @forelse($products as $p)
        <div class="product-card">
            <div class="product-image">
                <img src="{{ url('http://localhost:5000/' . ltrim($p['foto'], '/')) }}" alt="{{ $p['nama'] }}" style="object-fit:cover;">
                <button class="favorite-btn">
                    <i class="far fa-heart"></i>
                </button>
            </div>
            <div class="product-info">
                <span class="product-category">{{ ucfirst($p['kategori'] ?? '-') }}</span>
                <h3 class="product-name">{{ $p['nama'] }}</h3>
                <p class="product-unit">{{ $p['satuan'] ?? '' }}</p>
                <div class="product-price">
                    <div class="price-info">
                        <span class="current-price">Rp {{ number_format($p['harga'],0,',','.') }}</span>
                    </div>
                    <button class="buy-btn"
                        @if(!session('user')) onclick="window.location='{{ route('login') }}'" @endif
                        data-id="{{ $p['_id'] }}">
                        <i class="fas fa-shopping-cart"></i> Beli
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-3">Belum ada produk tersedia.</div>
    @endforelse
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.buy-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // <- TAMBAH DI PALING ATAS!
            console.log('Tombol Beli diklik'); // Debug, cek di Console browser

            @if(session('user'))
                var produkId = btn.getAttribute('data-id');
                fetch("{{ route('keranjang.tambah') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        produk: produkId,
                        jumlah: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.cart) {
                        alert('Berhasil ditambah ke keranjang!');
                        if(data.cart.items) {
                            document.querySelectorAll('.cart-badge').forEach(function(badge) {
                                badge.textContent = data.cart.items.length;
                            });
                        }
                    } else {
                        alert(data.message || 'Gagal menambah ke keranjang');
                    }
                })
                .catch(() => alert('Gagal koneksi ke server!'));
            @endif
        });
    });
});

</script>
@endpush
