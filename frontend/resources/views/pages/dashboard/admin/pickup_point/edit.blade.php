@extends('layouts.dashboard.main')
@section('title', 'Edit Pickup Point')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow border-0">
                <div class="card-header pb-0">
                    <h4 class="mb-0">Edit Pickup Point</h4>
                </div>
                <div class="card-body pt-3">
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form action="{{ route('admin.pickup_point.update', $point['_id']) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pickup Point <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $point['nama'] ?? '') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <input type="text" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                value="{{ old('alamat', $point['alamat'] ?? '') }}" required>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam_operasional" class="form-label">Jam Operasional <span class="text-danger">*</span></label>
                                <input type="text" name="jam_operasional" id="jam_operasional" class="form-control @error('jam_operasional') is-invalid @enderror"
                                    value="{{ old('jam_operasional', $point['jam_operasional'] ?? '') }}" required>
                                @error('jam_operasional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kontak" class="form-label">Kontak <span class="text-danger">*</span></label>
                                <input type="text" name="kontak" id="kontak" class="form-control @error('kontak') is-invalid @enderror"
                                    value="{{ old('kontak', $point['kontak'] ?? '') }}" required>
                                @error('kontak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif" {{ old('status', $point['status'] ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non-aktif" {{ old('status', $point['status'] ?? '') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $point['keterangan'] ?? '') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.pickup_point.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-rounded align-middle" style="font-size:20px;">save</i>
                                <span class="align-middle">Update Pickup Point</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
