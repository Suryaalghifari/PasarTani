@extends('layouts.dashboard.main')

@section('content')
<div class="container-fluid px-2 px-md-4">
  <div class="page-header min-height-300 border-radius-xl mt-4"
    style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?auto=format&fit=crop&w=1920&q=80');">
    <span class="mask bg-gradient-dark opacity-6"></span>
  </div>
  <div class="card card-body mx-2 mx-md-2 mt-n6">

    {{-- Flash message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row gx-4 mb-2">
      <div class="col-auto">
        <div class="avatar avatar-xl position-relative">
          <img src="{{ $user['foto_url'] }}" alt="Foto Profil" class="w-100 border-radius-lg shadow-sm">
        </div>
      </div>
      <div class="col-auto my-auto">
        <div class="h-100">
          <h5 class="mb-1">{{ $user['nama'] ?? '-' }}</h5>
          <p class="mb-0 font-weight-normal text-sm">
            {{ $user['alamat'] ?? '-' }}
          </p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
        <div class="text-end">
          <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-user-edit"></i> Edit Profil
          </button>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 col-md-6 offset-md-3">
        <div class="card card-plain">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Informasi Profil</h6>
          </div>
          <div class="card-body p-3">
          <ul class="list-group">
            <li class="list-group-item border-0 ps-0 pt-0 text-sm">
                <strong class="text-dark">Nama Lengkap:</strong> &nbsp;{{ $user['nama'] ?? '-' }}
            </li>
            <li class="list-group-item border-0 ps-0 text-sm">
                <strong class="text-dark">Alamat:</strong> &nbsp;{{ $user['alamat'] ?? '-' }}
            </li>
            <li class="list-group-item border-0 ps-0 text-sm">
                <strong class="text-dark">Nomor HP:</strong> &nbsp;{{ $user['no_hp'] ?? '-' }}
            </li>
            <li class="list-group-item border-0 ps-0 text-sm">
                <strong class="text-dark">Role:</strong> &nbsp;{{ ucfirst($role ?? '-') }}
            </li>
            <li class="list-group-item border-0 ps-0 text-sm">
                <strong class="text-dark">Foto Profil:</strong>
                <img src="{{ $user['foto_url'] }}" alt="Foto Profil" width="60" class="rounded-circle ms-2 shadow">
            </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header bg-gradient-primary text-white rounded-top-4">
            <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
         <form action="{{ route('petugas.profile.update', ['id' => $user['_id']]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
            @csrf
            <div class="modal-body p-4">
              <div class="mb-3">
                <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" class="form-control rounded-3 shadow-sm" name="nama" value="{{ $user['nama'] ?? '' }}" required>
              </div>
              <div class="mb-3">
                <label for="alamat" class="form-label fw-semibold">Alamat</label>
                <input type="text" class="form-control rounded-3 shadow-sm" name="alamat" value="{{ $user['alamat'] ?? '' }}">
              </div>
              <div class="mb-3">
                <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                <input type="text" class="form-control rounded-3 shadow-sm" name="no_hp" value="{{ $user['no_hp'] ?? '' }}">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password Baru <span class="text-muted">(opsional)</span></label>
                <input type="password" class="form-control rounded-3 shadow-sm" name="password" placeholder="Kosongkan jika tidak ingin mengganti">
              </div>
              <div class="mb-3">
                <label for="foto" class="form-label fw-semibold">Foto Profil</label>
                <input type="file" class="form-control rounded-3 shadow-sm" name="foto" accept="image/*">
                <div class="mt-2">
                  <img src="{{ $user['foto_url'] }}" alt="Foto Profil" width="60" class="rounded-circle shadow">
                </div>
              </div>
            </div>
            <div class="modal-footer border-0 pb-4 pt-0">
              <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End Modal -->
  </div>
</div>
@endsection
