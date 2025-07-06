@extends('layouts.home.app')
@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="main-content py-4" style="min-height:80vh;background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container">
        <div class="content-grid row gy-4 d-flex flex-lg-row flex-column">
            <!-- Left Column -->
            <div class="left-column col-lg-7 col-12 mb-4">
                <!-- Page Title Card -->
                <div class="page-title-card bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="page-title-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px;height:48px;">
                            <i class="fas fa-upload fa-lg"></i>
                        </div>
                        <div>
                            <h2 class="mb-0" style="font-weight:700;">Upload Bukti Pembayaran</h2>
                            <div class="text-muted">ID Pesanan: <span>{{ $checkout['kode_pesanan'] ?? '-' }}</span></div>
                        </div>
                    </div>
                    <!-- Progress Steps -->
                    <div class="progress-steps d-flex align-items-center mb-2">
                        <div class="step completed">
                            <div class="step-icon"><i class="fas fa-check"></i></div>
                            <span class="step-label">Pesanan Dibuat</span>
                        </div>
                        <div class="step-line completed flex-grow-1 mx-1" style="height:2px;background:#22c55e;"></div>
                        <div class="step active">
                            <div class="step-icon">2</div>
                            <span class="step-label">Menunggu Pembayaran</span>
                        </div>
                        <div class="step-line flex-grow-1 mx-1" style="height:2px;background:#cbd5e1;"></div>
                        <div class="step">
                            <div class="step-icon">3</div>
                            <span class="step-label">Pembayaran Dikonfirmasi</span>
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="bank-info-card bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="card-header-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h4 class="mb-0">Informasi Pembayaran</h4>
                    </div>
                    <div class="bank-accounts mb-3">
                        <div class="bank-account d-flex mb-2 align-items-center">
                            <i class="fas fa-university text-info me-2"></i>
                            <span class="bank-name me-2">Bank BCA:</span>
                            <span class="account-number fw-bold me-2" id="bcaNumber">1234567890</span>
                            <button type="button" onclick="copyToClipboard('bcaNumber', 'BCA')" class="btn btn-sm btn-light border"><i class="fas fa-copy"></i></button>
                        </div>
                        <div class="bank-account d-flex mb-2 align-items-center">
                            <i class="fas fa-university text-warning me-2"></i>
                            <span class="bank-name me-2">Bank Mandiri:</span>
                            <span class="account-number fw-bold me-2" id="mandiriNumber">0987654321</span>
                            <button type="button" onclick="copyToClipboard('mandiriNumber', 'Mandiri')" class="btn btn-sm btn-light border"><i class="fas fa-copy"></i></button>
                        </div>
                        <div class="mt-1 text-muted ms-4">Atas Nama: <b>PT TaniDirect Indonesia</b></div>
                    </div>
                    <div class="alert alert-warning small d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            Transfer sesuai nominal: <b>Rp{{ number_format($checkout['total_harga'] ?? 0,0,',','.') }}</b>,
                            <span>maksimal 24 jam setelah pesanan dibuat.</span>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="upload-form-card bg-white rounded-4 shadow-sm p-4">
                    {{-- Notifikasi error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('payment.upload.submit', $checkout['_id']) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <!-- Upload Area -->
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">
                                <i class="fas fa-cloud-upload-alt"></i> Upload Bukti Pembayaran <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*,application/pdf" required>
                            <small class="form-text text-muted">
                                Format JPG, PNG, PDF. Max 5MB.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label"><i class="fas fa-sticky-note"></i> Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2"></textarea>
                        </div>
                        <button class="btn btn-success px-4" type="submit">
                            <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="right-column col-lg-5 col-12 mb-4">
                <div class="order-summary-card bg-white rounded-4 shadow-sm p-4 mb-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="card-header-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h4 class="mb-0">Ringkasan Pesanan</h4>
                    </div>
                    <!-- Order Items -->
                    <div class="order-items mb-2">
                        @foreach($checkout['cart_items'] as $item)
                            <div class="order-item d-flex align-items-center mb-2">
                                <img src="" data-produk="{{ $item['produk'] }}" alt="{{ $item['nama_produk'] }}" class="product-image order-item-img-{{ $item['produk'] }}" style="width:48px;height:48px;border-radius:8px;object-fit:cover;">
                                <div class="ms-2 flex-grow-1">
                                    <div class="fw-bold">{{ $item['nama_produk'] ?? '-' }}</div>
                                    <small>{{ $item['jumlah'] ?? 1 }} x Rp{{ number_format($item['subtotal'] ?? 0,0,',','.') }}</small>
                                </div>
                                <span class="fw-bold text-success">Rp{{ number_format($item['subtotal'] ?? 0,0,',','.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <!-- Order Total -->
                    <div class="order-total border-top pt-2 d-flex justify-content-between fw-bold mb-2">
                        <span>Total:</span>
                        <span class="total-amount text-success">Rp{{ number_format($checkout['total_harga'] ?? 0,0,',','.') }}</span>
                    </div>
                    <!-- Order Details -->
                    <div class="order-details small text-muted">
                        <div class="mb-1"><i class="fas fa-credit-card"></i> Metode: <b>{{ strtoupper($checkout['metode_pembayaran']) }}</b></div>
                        <div class="mb-1"><i class="fas fa-map-marker-alt"></i> Pickup Point: <b>{{ $checkout['id_titik_ambil']['nama'] ?? '-' }}{{ isset($checkout['id_titik_ambil']['alamat']) ? ' - '.$checkout['id_titik_ambil']['alamat'] : '' }}</b></div>
                        <div class="mb-1"><i class="fas fa-home"></i> Alamat Pengiriman: <b>{{ $checkout['alamat_pengiriman'] ?? '-' }}</b></div>
                        <div class="mb-1"><i class="fas fa-phone"></i> No HP: <b>{{ $checkout['no_hp'] ?? '-' }}</b></div>
                        @if(!empty($checkout['catatan']))
                        <div class="mb-1"><i class="fas fa-sticky-note"></i> Catatan: <b>{{ $checkout['catatan'] }}</b></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom Note -->
        <div class="bottom-note bg-white mt-4 p-3 rounded-4 shadow-sm d-flex align-items-center">
            <i class="fas fa-info-circle me-2 text-primary"></i>
            <div>
                <b>Note:</b> Silakan upload bukti transfer sesuai instruksi pembayaran.
                <br><b>Nominal:</b> Rp{{ number_format($checkout['total_harga'] ?? 0,0,',','.') }}, 
                <b>Metode:</b> {{ strtoupper($checkout['metode_pembayaran']) }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(elementId, bank) {
    var text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text);
    alert('No. Rekening ' + bank + ' berhasil disalin: ' + text);
}

// Script untuk menampilkan gambar produk
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('img[data-produk]').forEach(function(imgEl) {
        var produkId = imgEl.getAttribute('data-produk');
        if (produkId) {
            fetch('http://192.168.100.41:5000/api/products/' + produkId)
            .then(res => res.json())
            .then(data => {
                if (data && data.foto) {
                    imgEl.src = 'http://192.168.100.41:5000/' + data.foto.replace(/^\//, '');
                } else {
                    imgEl.src = "https://ui-avatars.com/api/?name=Produk&background=random";
                }
            })
            .catch(() => {
                imgEl.src = "https://ui-avatars.com/api/?name=Produk&background=random";
            });
        } else {
            imgEl.src = "https://ui-avatars.com/api/?name=Produk&background=random";
        }
    });
});
</script>
@endpush
