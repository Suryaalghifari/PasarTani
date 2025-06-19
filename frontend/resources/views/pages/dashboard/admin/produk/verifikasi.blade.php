@extends('layouts.dashboard.main')
@section('title', 'Verifikasi Produk')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Verifikasi Produk Petani</h6>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Foto</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Produk</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kategori</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Petani</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email Petani</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($products as $p)
            <tr>
              <td>
                @if(!empty($p['foto']))
                  <img src="{{ 'http://localhost:5000/' . ltrim($p['foto'],'/') }}" alt="Foto Produk" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>
                <h6 class="mb-0 text-sm">{{ $p['nama'] }}</h6>
              </td>
              <td>
                <span class="badge badge-sm bg-gradient-{{ 
                  $p['kategori'] === 'sayur' ? 'success' : 
                  ($p['kategori'] === 'buah' ? 'primary' :
                  ($p['kategori'] === 'herbal' ? 'warning text-dark' : 'secondary')) }}">
                  {{ ucfirst($p['kategori'] ?? '-') }}
                </span>
              </td>
              <td>
                <span class="text-xs">{{ $p['id_petani']['nama'] ?? '-' }}</span>
              </td>
              <td>
                <span class="text-xs">{{ $p['id_petani']['email'] ?? '-' }}</span>
              </td>
              <td>
                <span class="badge badge-sm bg-gradient-warning text-dark">
                  Menunggu Verifikasi
                </span>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">
                  {{ \Carbon\Carbon::parse($p['createdAt'])->format('d/m/Y H:i') }}
                </span>
              </td>
              <td class="align-middle text-center">
                <a href="{{ route('admin.produk.detail', $p['_id']) }}" class="btn btn-sm btn-outline-info" title="Detail">
                  <i class="fas fa-eye"></i> Detail
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted">Tidak ada produk menunggu verifikasi.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
