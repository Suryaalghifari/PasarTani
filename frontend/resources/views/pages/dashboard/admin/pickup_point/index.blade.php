@extends('layouts.dashboard.main')
@section('title', 'Daftar Pickup Point')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Daftar Pickup Point</h6>
        <a href="{{ route('admin.pickup_point.create') }}" class="btn btn-sm btn-light text-primary me-3">
          + Tambah Pickup Point
        </a>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jam Operasional</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kontak</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pickupPoints as $p)
              <tr>
                <td>
                  <h6 class="mb-0 text-sm">{{ $p['nama'] }}</h6>
                </td>
                <td>
                  <span class="text-xs">{{ $p['alamat'] }}</span>
                </td>
                <td>
                  <span class="text-xs">{{ $p['jam_operasional'] }}</span>
                </td>
                <td>
                  <span class="text-xs">{{ $p['kontak'] }}</span>
                </td>
                <td>
                  <span class="badge badge-sm bg-gradient-{{ $p['status'] === 'aktif' ? 'success' : 'secondary' }}">
                    {{ ucfirst($p['status']) }}
                  </span>
                </td>
                <td>
                  <span class="text-xs">{{ $p['keterangan'] ?? '-' }}</span>
                </td>
                <td class="align-middle text-center">
                  <a href="{{ route('admin.pickup_point.edit', $p['_id']) }}" class="btn btn-sm btn-outline-info me-1" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('admin.pickup_point.destroy', $p['_id']) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">Belum ada pickup point</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
