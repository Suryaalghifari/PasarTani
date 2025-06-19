@extends('layouts.dashboard.main')
@section('title', 'Detail Pengiriman')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow border-0">
                <div class="card-header pb-0">
                    <h4 class="mb-0">Detail Logistik</h4>
                </div>
                <div class="card-body pt-3">
                    @if($logistik)
                        <div class="mb-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="fw-semibold">Order ID:</span>
                                    <span>{{ $logistik['id_order']['_id'] ?? '-' }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="fw-semibold">Status Pengiriman:</span>
                                    <span>
                                        <span class="badge bg-gradient-{{ 
                                            $logistik['status_pengiriman'] == 'selesai' ? 'success' :
                                            ($logistik['status_pengiriman'] == 'dalam-perjalanan' ? 'primary' :
                                            ($logistik['status_pengiriman'] == 'tiba-di-titik-ambil' ? 'warning text-dark' :
                                            ($logistik['status_pengiriman'] == 'gagal' ? 'danger' : 'secondary'))) }}">
                                            {{ strtoupper(str_replace('-', ' ', $logistik['status_pengiriman'])) }}
                                        </span>
                                    </span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="fw-semibold">Kurir:</span>
                                    <span>{{ $logistik['kurir'] ?? '-' }}</span>
                                </li>
                                <!-- Tambahkan detail lain di sini jika perlu -->
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('petugas.logistik.update_status', $logistik['_id']) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="status_pengiriman" class="form-label">Status Pengiriman <span class="text-danger">*</span></label>
                                <select name="status_pengiriman" id="status_pengiriman" class="form-select" required>
                                    <option value="menunggu" {{ $logistik['status_pengiriman'] == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="dalam-perjalanan" {{ $logistik['status_pengiriman'] == 'dalam-perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                    <option value="tiba-di-titik-ambil" {{ $logistik['status_pengiriman'] == 'tiba-di-titik-ambil' ? 'selected' : '' }}>Tiba di Titik Ambil</option>
                                    <option value="selesai" {{ $logistik['status_pengiriman'] == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="gagal" {{ $logistik['status_pengiriman'] == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="2" class="form-control">{{ $logistik['catatan'] ?? '' }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="{{ route('petugas.logistik') }}" class="btn btn-secondary me-2">Kembali</a>
                                <button class="btn btn-success" type="submit">
                                    <i class="material-symbols-rounded align-middle" style="font-size:20px;">save</i>
                                    <span class="align-middle">Update Status</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">Data logistik tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
