@extends('layouts.dashboard.main')
@section('title', 'Edit Produk')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow border-0">
                <div class="card-header pb-0">
                    <h4 class="mb-0">Edit Produk</h4>
                </div>
                <div class="card-body pt-3">
                    <form action="{{ route('petani.produk.update', $produk['_id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $produk['nama']) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $produk['harga']) }}" min="0" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $produk['stok']) }}" min="1" required>
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="sayur" {{ old('kategori', $produk['kategori']) == 'sayur' ? 'selected' : '' }}>Sayur</option>
                                <option value="buah" {{ old('kategori', $produk['kategori']) == 'buah' ? 'selected' : '' }}>Buah</option>
                                <option value="herbal" {{ old('kategori', $produk['kategori']) == 'herbal' ? 'selected' : '' }}>Herbal</option>
                                <option value="produk-lain" {{ old('kategori', $produk['kategori']) == 'produk-lain' ? 'selected' : '' }}>Produk Lain</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $produk['deskripsi']) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gambar" class="form-label">Ganti Foto Produk</label>
                            <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(!empty($produk['foto']))
                                <div class="mt-3">
                                    <span class="d-block mb-1 text-muted">Foto Produk Saat Ini:</span>
                                    <img src="{{ 'http://localhost:5000/' . ltrim($produk['foto'],'/') }}" alt="Foto Produk" style="width:90px; border-radius:0.5rem; box-shadow:0 2px 8px rgba(0,0,0,.1)">
                                </div>
                            @endif
                            <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti foto.</small>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('petani.produk.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-rounded align-middle" style="font-size:20px;">save</i>
                                <span class="align-middle">Update Produk</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
