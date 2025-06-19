@extends('layouts.dashboard.main')
@section('title', 'Data Logistik')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Data Pengiriman / Logistik</h6>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order ID</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Petani</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pickup Point</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jadwal Kirim</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Pengiriman</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logistics as $l)
              <tr>
                <td>
                  <a href="{{ route('admin.logistik.detail', $l['id_order']['_id'] ?? '-') }}" class="text-primary text-xs font-weight-bold">
                    {{ $l['id_order']['_id'] ?? '-' }}
                  </a>
                </td>
                <td>
                  <span class="text-xs">{{ $l['id_petani']['nama'] ?? '-' }}</span>
                </td>
                <td>
                  <span class="text-xs">{{ $l['id_titik_ambil']['nama'] ?? '-' }}</span><br>
                  <small class="text-secondary">{{ $l['id_titik_ambil']['alamat'] ?? '' }}</small>
                </td>
                <td>
                  @if(!empty($l['jadwal_pengiriman']))
                    <span class="text-xs">{{ date('d-m-Y H:i', strtotime($l['jadwal_pengiriman'])) }}</span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  <span class="badge badge-sm bg-gradient-{{ 
                    $l['status_pengiriman'] === 'selesai' ? 'success' : 
                    ($l['status_pengiriman'] === 'dikirim' ? 'primary' : 'secondary') }}">
                    {{ strtoupper($l['status_pengiriman']) }}
                  </span>
                </td>
                <td class="align-middle text-center">
                  <a href="{{ route('admin.logistik.detail', $l['id_order']['_id'] ?? '-') }}" class="btn btn-sm btn-outline-info" title="Detail">
                    <i class="fas fa-eye"></i> Detail
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data logistik.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
