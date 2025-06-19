@extends('layouts.dashboard.main')
@section('title', 'Pesanan Produk Saya')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Pesanan Produk Saya</h6>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Konsumen</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($orders as $order)
            <tr>
              <td>
                <span class="text-secondary text-xs font-weight-bold">
                  {{ \Carbon\Carbon::parse($order['createdAt'])->format('d/m/Y H:i') }}
                </span>
              </td>
              <td>
                <ul class="mb-0 ps-3">
                    @foreach($order['items'] as $item)
                      <li class="text-xs">
                        {{ $item['nama_produk'] ?? '-' }} 
                        <span class="text-secondary">x{{ $item['jumlah'] ?? 1 }}</span>
                      </li>
                    @endforeach
                </ul>
              </td>
              <td>
                <span class="text-xs">{{ $order['id_konsumen']['nama'] ?? '-' }}</span>
              </td>
              <td>
                <span class="text-xs font-weight-bold">Rp{{ number_format($order['total_harga'],0,',','.') }}</span>
              </td>
              <td class="align-middle">
                <span class="badge badge-sm bg-gradient-{{ 
                  ($order['status_pesanan'] ?? '') === 'menunggu-proses' ? 'info' : 
                  (($order['status_pesanan'] ?? '') === 'siap-diambil' ? 'success' : 'secondary') }}">
                  {{ ucfirst(str_replace('-',' ', $order['status_pesanan'] ?? '-')) }}
                </span>
              </td>
              <td class="align-middle text-center">
                @if(($order['status_pesanan'] ?? '') === 'menunggu-proses')
                  <form action="{{ route('petani.order.update', $order['_id']) }}" method="POST" style="display:inline;">
                      @csrf
                      <input type="hidden" name="status_pesanan" value="siap-diambil">
                      <button class="btn btn-success btn-sm"
                        onclick="return confirm('Tandai pesanan ini sebagai siap diambil?')">
                        <i class="fas fa-check"></i> Siap Diambil
                      </button>
                  </form>
                @elseif(($order['status_pesanan'] ?? '') === 'siap-diambil')
                  <span class="badge bg-gradient-success text-white px-3 py-2" style="font-size:.97rem;">
                    Menunggu Pengambilan
                  </span>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Belum ada pesanan masuk.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
