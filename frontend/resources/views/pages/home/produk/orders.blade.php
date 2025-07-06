@extends('layouts.home.app')
@section('title', 'Pesanan Saya')

@section('content')
<main class="main-content">
    <div class="container">
        <section class="orders-section">
            <div class="section-header">
                <h1 class="page-title">Pesanan Saya</h1>
                <div class="title-decoration"></div>
            </div>
            <div class="orders-card">
                <div class="table-wrapper" style="overflow-x:auto;">
                    @if(count($orders) > 0)
                    <table class="orders-table" style="min-width:700px;">
                        <thead>
                            <tr>
                                <th>
                                    <div class="th-content">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Tanggal</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="th-content">
                                        <i class="fas fa-box"></i>
                                        <span>Produk</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="th-content">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>Total</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="th-content">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Status Pesanan</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="order-row">
                                <td>
                                    <div class="date-cell">
                                        <span class="date-main">
                                            {{ \Carbon\Carbon::parse($order['createdAt'])->format('d-m-Y') }}
                                        </span>
                                        <span class="time-sub">
                                            {{ \Carbon\Carbon::parse($order['createdAt'])->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $items = $order['items'] ?? $order['cart_items'] ?? [];
                                    @endphp

                                    @if(count($items) == 1)
                                        @php
                                            $item = $items[0];
                                            $produkId = is_array($item['produk']) ? ($item['produk']['_id'] ?? '') : $item['produk'];
                                            $namaProduk = is_array($item['nama_produk'] ?? null) ? '-' : ($item['nama_produk'] ?? '-');
                                        @endphp
                                        <div class="product-cell">
                                            <div class="product-icon">
                                                <img 
                                                    src="" 
                                                    alt="{{ $namaProduk }}" 
                                                    class="order-img-{{ $produkId }}" 
                                                    data-produk="{{ $produkId }}"
                                                    style="width:28px;height:28px;object-fit:cover;border-radius:50%;">
                                            </div>
                                            <span>
                                                {{ $namaProduk }} x{{ $item['jumlah'] ?? 1 }}
                                            </span>
                                        </div>
                                    @elseif(count($items) > 1)
                                        <div class="product-cell multi-product">
                                            <div class="product-list">
                                                @foreach($items as $item)
                                                    @php
                                                        $produkId = is_array($item['produk']) ? ($item['produk']['_id'] ?? '') : $item['produk'];
                                                        $namaProduk = is_array($item['nama_produk'] ?? null) ? '-' : ($item['nama_produk'] ?? '-');
                                                    @endphp
                                                    <div class="product-item d-flex align-items-center">
                                                        <img 
                                                            src="" 
                                                            alt="{{ $namaProduk }}" 
                                                            class="order-img-{{ $produkId }}"
                                                            data-produk="{{ $produkId }}"
                                                            style="width:20px;height:20px;object-fit:cover;border-radius:50%;margin-right:6px;">
                                                        <span>
                                                            {{ $namaProduk }} x{{ $item['jumlah'] ?? 1 }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="price-cell">
                                        <span class="currency">Rp</span>
                                        <span class="amount">{{ number_format($order['total_harga'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $status = $order['status_pesanan'] ?? $order['status_checkout'] ?? null;
                                        $statusLower = strtolower($status ?? '');
                                    @endphp
                                    @if($status)
                                        @if($statusLower === 'menunggu verifikasi')
                                            <span class="status status-verification text-warning">
                                                <i class="fas fa-clock"></i>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($statusLower === 'pending')
                                            <span class="status status-pending text-secondary">
                                                <i class="fas fa-hourglass-half"></i>
                                                Pending
                                            </span>
                                        @elseif($statusLower === 'selesai')
                                            <span class="status status-done text-success">
                                                <i class="fas fa-check-circle"></i>
                                                Selesai
                                            </span>
                                        @elseif(
                                            $statusLower === 'ditolak' ||
                                            $statusLower === 'gagal' ||
                                            $statusLower === 'batal' ||
                                            $statusLower === 'rejected' ||
                                            $statusLower === 'cancelled'
                                        )
                                            <span class="status status-cancelled text-danger">
                                                <i class="fas fa-times-circle"></i>
                                                {{ ucfirst($statusLower) }}
                                            </span>
                                        @else
                                            <span class="status status-other text-muted">
                                                <i class="fas fa-question-circle"></i>
                                                {{ ucfirst($status) }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="status status-other text-muted">
                                            <i class="fas fa-question-circle"></i>
                                            Tidak diketahui
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info mt-4">
                        Belum ada pesanan.<br>
                        Pesanan akan muncul setelah kamu checkout.
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-produk]').forEach(function(imgEl) {
        var produkId = imgEl.getAttribute('data-produk');
        if (produkId) {
            fetch('http://192.168.100.41:5000/api/products/' + produkId)
            .then(res => res.json())
            .then(data => {
                if (data && data.foto) {
                    imgEl.src = 'http://192.168.100.41:5000/' + data.foto.replace(/^\//, '');
                } else {
                    imgEl.src = "https://ui-avatars.com/api/?name=Produk&background=random";
                }
            })
            .catch(() => {
                imgEl.src = "https://ui-avatars.com/api/?name=Produk&background=random";
            });
        }
    });
});
</script>
@endpush
