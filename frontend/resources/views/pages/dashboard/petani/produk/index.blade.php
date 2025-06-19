@extends('layouts.dashboard.main')
@section('title', 'Daftar Produk Saya')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Daftar Produk Saya</h6>
        <a href="{{ route('petani.produk.create') }}" class="btn btn-sm btn-light text-primary me-3">
            + Tambah Produk Baru
        </a>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Foto</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kategori</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stok</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Status</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Tanggal</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($products as $p)
            <tr>
              <td>
                @if(!empty($p['foto']))
                  <img src="{{ $p['foto_url'] }}" alt="Foto Produk" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>
                <h6 class="mb-0 text-sm">{{ $p['nama'] }}</h6>
              </td>
              <td>
                <p class="text-xs text-secondary mb-0">{{ Str::limit($p['deskripsi'] ?? '', 40) }}</p>
              </td>
              <td>
                <span class="badge badge-sm bg-gradient-{{ 
                  $p['kategori'] === 'sayur' ? 'success' : 
                  ($p['kategori'] === 'buah' ? 'primary' :
                  ($p['kategori'] === 'herbal' ? 'warning text-dark' : 'secondary')) }}">
                  {{ ucfirst(str_replace('-',' ', $p['kategori'] ?? '-')) }}
                </span>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($p['harga'] ?? 0,0,',','.') }}</p>
              </td>
              <td>
                <span class="text-xs text-secondary">{{ $p['stok'] ?? 0 }}</span>
              </td>
              <td class="align-middle text-center text-sm">
                <span class="badge badge-sm bg-gradient-{{ 
                  $p['status'] === 'tunggu-verifikasi' ? 'secondary' : 
                  ($p['status'] === 'tersedia' ? 'success' : 
                  ($p['status'] === 'ditolak' ? 'danger' : 'warning text-dark')) }}">
                  {{ ucfirst(str_replace('-',' ', $p['status'] ?? '-')) }}
                </span>
              </td>
              <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">
                  {{ \Carbon\Carbon::parse($p['createdAt'] ?? $p['created_at'] ?? now())->format('d/m/Y H:i') }}
                </span>
              </td>
              <td class="align-middle text-center">
                <a href="{{ route('petani.produk.edit', $p['_id']) }}" class="btn btn-sm btn-outline-info me-1" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('petani.produk.destroy', $p['_id']) }}" method="POST" style="display:inline;" class="form-hapus-produk">
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
              <td colspan="9" class="text-center text-muted">Belum ada produk</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Untuk semua form hapus produk
    document.querySelectorAll('.form-hapus-produk').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin hapus produk ini?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush

@endsection
