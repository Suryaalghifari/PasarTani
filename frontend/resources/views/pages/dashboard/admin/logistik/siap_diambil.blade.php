@extends('layouts.dashboard.main')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">
          Daftar Pesanan Siap Diambil (Belum Ada Jadwal Pengiriman)
        </h6>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      @if(count($ordersSiapDiambil) > 0)
        <div class="table-responsive p-3">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order ID</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Konsumen</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ordersSiapDiambil as $order)
                <tr>
                  <td>
                    <span class="text-xs font-weight-bold">{{ $order['_id'] }}</span>
                  </td>
                  <td>
                    <span class="text-xs">{{ $order['id_konsumen']['nama'] ?? '-' }}</span>
                  </td>
                  <td>
                    <span class="text-xs">{{ $order['alamat_pengiriman'] ?? '-' }}</span>
                  </td>
                  <td>
                    <span class="text-xs font-weight-bold">Rp{{ number_format($order['total_harga'] ?? 0, 0, ',', '.') }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="{{ route('admin.logistik.atur_jadwal', $order['_id']) }}" class="btn btn-sm btn-success">
                      <i class="fas fa-calendar-alt"></i> Atur Pengiriman
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-info mx-4 mt-4">
          Tidak ada pesanan siap diambil tanpa logistik.
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
