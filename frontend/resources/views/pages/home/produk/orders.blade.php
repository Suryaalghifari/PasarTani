@extends('layouts.home.app')
@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">
    <h2>Pesanan Saya</h2>
    @if(count($orders) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($order['createdAt'])->format('d-m-Y H:i') }}</td>
                        <td>
                            <ul>
                                @foreach(($order['items'] ?? $order['cart_items']) as $item)
                                    <li>{{ $item['nama_produk'] ?? '-' }} x{{ $item['jumlah'] ?? 1 }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ number_format($order['total_harga'] ?? 0) }}</td>
                        <td>
                            @if(isset($order['status_pesanan']))
                                <span class="badge bg-info">{{ ucfirst($order['status_pesanan']) }}</span>
                            @elseif(isset($order['status_checkout']))
                                <span class="badge bg-warning text-dark">{{ str_replace('-', ' ', ucfirst($order['status_checkout'])) }}</span>
                            @else
                                <span class="badge bg-secondary">Tidak diketahui</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Belum ada pesanan.<br>
            Pesanan akan muncul setelah kamu checkout.
        </div>
    @endif
</div>
@endsection
