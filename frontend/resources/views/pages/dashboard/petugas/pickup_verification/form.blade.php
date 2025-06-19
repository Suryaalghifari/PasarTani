@extends('layouts.dashboard.main')
@section('title', 'Verifikasi Pickup Order')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow border-0">
                <div class="card-header pb-0">
                    <h4 class="mb-0">Verifikasi Pickup Order</h4>
                </div>
                <div class="card-body pt-3">
                    <form action="{{ route('petugas.pickup_verification.upload', $order['id_order']['_id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Pickup Point</label>
                            <input type="text" class="form-control" value="{{ $order['id_titik_ambil']['nama'] ?? '-' }}" readonly>
                            <input type="hidden" name="id_titik_ambil" value="{{ $order['id_titik_ambil']['_id'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konsumen</label>
                            <input type="text" class="form-control" value="{{ $order['id_order']['id_konsumen']['nama'] ?? '-' }}" readonly>
                            <input type="hidden" name="id_konsumen" value="{{ $order['id_order']['id_konsumen']['_id'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="waktu_verifikasi" class="form-label">Waktu Verifikasi <span class="text-danger">*</span></label>
                            <input type="time" name="waktu_verifikasi" id="waktu_verifikasi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="foto_bukti" class="form-label">Upload Foto Bukti Pickup <span class="text-danger">*</span></label>
                            <input type="file" name="foto_bukti" id="foto_bukti" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('petugas.logistik') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-success">
                                <i class="material-symbols-rounded align-middle" style="font-size:20px;">cloud_upload</i>
                                <span class="align-middle">Kirim Bukti Pickup</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
