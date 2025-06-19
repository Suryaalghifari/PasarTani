@extends('layouts.dashboard.main')
@section('content')
<div class="container mt-4">
    <h3>Tambah User Baru</h3>
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="peran" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('peran') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petani" {{ old('peran') == 'petani' ? 'selected' : '' }}>Petani</option>
                <option value="petugas" {{ old('peran') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="pelanggan" {{ old('peran') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
        </div>
        <div class="mb-3">
            <label>Foto (opsional)</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Tambah User</button>
    </form>
</div>
@endsection
