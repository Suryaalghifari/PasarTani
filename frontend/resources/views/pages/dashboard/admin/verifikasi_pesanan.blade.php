@extends('layouts.dashboard.main')
@section('title', 'Verifikasi Pesanan')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Verifikasi Pembayaran Konsumen</h6>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      @if(session('success'))
        <div class="alert alert-success mx-4">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger mx-4">{{ $errors->first() }}</div>
      @endif
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelanggan</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bukti Pembayaran</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($checkouts as $checkout)
              <tr>
                <td>
                  <span class="text-secondary text-xs font-weight-bold">
                    {{ \Carbon\Carbon::parse($checkout['createdAt'])->format('d/m/Y H:i') }}
                  </span>
                </td>
                <td>
                  <span class="text-xs">{{ $checkout['id_konsumen']['nama'] ?? '-' }}</span>
                </td>
                <td>
                  <ul class="mb-0 ps-3">
                    @foreach($checkout['cart_items'] as $item)
                      <li class="text-xs">
                        {{ $item['nama_produk'] ?? '-' }} <span class="text-secondary">x{{ $item['jumlah'] ?? 1 }}</span>
                      </li>
                    @endforeach
                  </ul>
                </td>
                <td>
                  <span class="text-xs font-weight-bold">
                    Rp{{ number_format($checkout['total_harga'] ?? 0, 0, ',', '.') }}
                  </span>
                </td>
                <td>
                  @if($checkout['bukti_pembayaran'])
                    <a href="{{ asset($checkout['bukti_pembayaran']) }}" target="_blank" class="badge badge-sm bg-gradient-info text-white" title="Lihat Bukti">
                      <i class="fas fa-receipt"></i> Lihat
                    </a>
                  @else
                    <span class="badge badge-sm bg-gradient-danger">Belum upload</span>
                  @endif
                </td>
                <td class="align-middle text-center">
                  <form action="{{ route('admin.verifikasi_pesanan.aksi', $checkout['_id']) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-success btn-sm me-1" name="aksi" value="paid" onclick="return confirm('Setujui pembayaran ini?')">
                      <i class="fas fa-check"></i> Verifikasi
                    </button>
                    <button class="btn btn-danger btn-sm" name="aksi" value="gagal" onclick="return confirm('Tolak pembayaran ini?')">
                      <i class="fas fa-times"></i> Tolak
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted"><em>Tidak ada pesanan yang perlu diverifikasi.</em></td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
