@extends('layouts.home.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height:60vh;">
    <div class="col-lg-6 col-md-8 col-12">
        <div class="bg-white p-4 rounded-4 shadow-sm">
            <h2 class="mb-4" style="font-weight:700;">Keranjang Belanja</h2>
            @if($cart && count($cart['items']) > 0)
                @foreach($cart['items'] as $item)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom gap-3">
                        <img src="{{ $item['image_url'] ?? 'https://via.placeholder.com/60?text=No+Image' }}" alt="{{ $item['nama_produk'] ?? '' }}" class="rounded" style="width:60px; height:60px; object-fit:cover;">
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:1.1rem;">{{ $item['nama_produk'] ?? '-' }}</div>
                            <div class="text-muted" style="font-size:0.95rem;">
                                Rp{{ number_format($item['harga'] ?? 0,0,',','.') }} x {{ $item['jumlah'] ?? 1 }} {{ $item['satuan'] ?? '' }}
                            </div>
                        </div>
                        <div class="fw-bold me-2" style="font-size:1.07rem;">
                            Rp{{ number_format($item['subtotal'] ?? 0,0,',','.') }}
                        </div>
                        <button class="btn btn-sm btn-danger btn-hapus-produk" data-id="{{ $item['produk'] }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
                <div class="d-flex justify-content-between align-items-center mt-4" style="font-size:1.17rem; font-weight:600;">
                    <span>Total</span>
                    <span>Rp{{ number_format($cart['total_harga'] ?? 0,0,',','.') }}</span>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('checkout') }}" class="btn btn-success px-4 py-2" style="font-size:1.07rem; border-radius:10px;">Lanjut ke Checkout</a>
                </div>
            @else
                <div class="alert alert-info text-center my-5">
                    Keranjang kamu masih kosong.<br>
                    <a href="{{ route('home') }}" class="btn btn-outline-success mt-3">Belanja Sekarang</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-hapus-produk').forEach(function(btn) {
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

</script>
@endpush
