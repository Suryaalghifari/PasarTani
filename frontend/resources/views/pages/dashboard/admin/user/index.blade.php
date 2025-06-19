@extends('layouts.dashboard.main')
@section('title', 'Daftar User')

@section('content')
<div class="container-fluid py-4">
  <div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
        <h6 class="text-white ps-3 mb-0">Daftar User</h6>
        <a href="{{ route('admin.user.create') }}" class="btn btn-sm btn-light text-primary me-3">
          <i class="fas fa-user-plus me-1"></i> Tambah User
        </a>
      </div>
    </div>
    <div class="card-body px-0 pb-2">
      @if(session('success'))
        <div class="alert alert-success mx-4">{{ session('success') }}</div>
      @endif
      <div class="table-responsive p-3">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No HP</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Foto</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $u)
            <tr>
              <td>
                <span class="text-xs font-weight-bold">{{ $u['nama'] }}</span>
              </td>
              <td>
                <span class="text-xs">{{ $u['email'] }}</span>
              </td>
              <td>
                <span class="badge badge-sm bg-gradient-{{ 
                    $u['peran'] === 'admin' ? 'dark' : 
                    ($u['peran'] === 'petani' ? 'success' :
                    ($u['peran'] === 'konsumen' ? 'primary' : 'secondary')) }}">
                  {{ ucfirst($u['peran']) }}
                </span>
              </td>
              <td>
                <span class="text-xs">{{ $u['no_hp'] ?? '-' }}</span>
              </td>
              <td>
                <span class="text-xs">{{ $u['alamat'] ?? '-' }}</span>
              </td>
              <td>
                @if(!empty($u['foto']))
                  <img src="http://localhost:5000/uploads/{{ $u['foto'] }}" style="width:40px; height:40px; object-fit:cover; border-radius:50%;">
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td class="align-middle text-center">
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <a href="{{ route('admin.user.edit', $u['_id']) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('admin.user.destroy', $u['_id']) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
