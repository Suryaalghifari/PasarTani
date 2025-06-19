@extends('layouts.dashboard.main')
@section('title', 'Daftar Pengiriman Petugas')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Daftar Pengiriman/Pickup</h6>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pickup Point</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kurir</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logistics as $log)
            <tr>
              <td>
                <span class="text-xs font-weight-bold">{{ $log['id_order']['_id'] ?? '-' }}</span>
              </td>
              <td>
                <span class="text-xs">{{ $log['id_titik_ambil']['nama'] ?? '-' }}</span>
              </td>
              <td>
                <span class="text-xs">{{ $log['kurir'] ?? '-' }}</span>
              </td>
              <td>
                <span class="badge badge-sm bg-gradient-{{ 
                  ($log['status_pengiriman'] === 'selesai') ? 'success' : (
                    ($log['status_pengiriman'] === 'dikirim' ? 'primary' : 'secondary')
                  ) }}">
                  {{ ucfirst($log['status_pengiriman']) }}
                </span>
              </td>
              <td class="align-middle text-center">
                <a href="{{ route('petugas.logistik.detail', $log['id_order']['_id']) }}" class="btn btn-outline-info btn-sm me-1">
                  <i class="fas fa-eye"></i> Detail
                </a>
                @if($log['status_pengiriman'] !== 'selesai')
                  <a href="{{ route('petugas.pickup_verification.form', $log['id_order']['_id']) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-check-circle"></i> Verifikasi Pickup
                  </a>
                @else
                  <span class="badge bg-gradient-success">Selesai</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center text-muted">Belum ada pengiriman</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
