@extends('layouts.dashboard.main')
@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow border-0">
                <div class="card-header pb-0">
                    <h4 class="mb-0">Edit User</h4>
                </div>
                <div class="card-body pt-3">
                    @if(session('error'))
                      <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('admin.user.update', $user['_id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama', $user['nama']) }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email', $user['email']) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru <span class="text-muted">(Kosongkan jika tidak ingin ganti)</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="peran" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="peran" id="peran" class="form-select @error('peran') is-invalid @enderror" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petani" {{ $role == 'petani' ? 'selected' : '' }}>Petani</option>
                                <option value="petugas" {{ $role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="pelanggan" {{ $role == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                            </select>
                            @error('peran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ old('alamat', $user['alamat'] ?? '') }}">
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $user['no_hp'] ?? '') }}">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Profil</label>
                            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(!empty($user['foto']))
                              <div class="mt-3">
                                <span class="d-block mb-1 text-muted">Foto Profil Saat Ini:</span>
                                <img src="http://localhost:5000/uploads/{{ $user['foto'] }}" style="width:60px; height:60px; object-fit:cover; border-radius:50%;">
                              </div>
                            @endif
                            <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti foto.</small>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-rounded align-middle" style="font-size:20px;">save</i>
                                <span class="align-middle">Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
    