@extends('layouts.app')

@section('content')
<style>
    .bg-gradient-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7) !important;
    }
    .btn-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7);
        color: white;
        border: none;
    }
    .btn-pink-biru:hover {
        opacity: 0.9;
        color: white;
    }
    .card-custom {
        border-radius: 15px;
        border: none;
    }
    .img-qr {
        border: 4px solid #f06292;
        border-radius: 10px;
        padding: 5px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg card-custom overflow-hidden">
            <div class="card-header bg-gradient-pink-biru text-white text-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-qr-code-scan me-2"></i>Scan QRIS & Input Bayar</h5>
            </div>
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <h6 class="fw-bold text-muted">QR Pembayaran Kas Kelas</h6>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PEMBAYARAN-KAS-XI-RPL-2" 
                         alt="QRIS Pembayaran" class="img-qr shadow-sm mb-3" style="width: 180px;">
                    <p class="small text-secondary px-3">Silakan scan dan bayar <strong>Rp5.000</strong> via Dana/OVO/Gopay sebelum mengisi form di bawah.</p>
                </div>

                <hr class="my-4 opacity-50">

               <form action="{{ route('siswa.bayar.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-muted">Nama Siswa</label>
                        <input type="text" class="form-control bg-light border-0 py-2 shadow-sm" value="{{ $siswa->nama }}" readonly>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-muted">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control py-2 shadow-sm" value="5000" min="5000" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-muted">Bukti Transfer (Screenshot)</label>
                        <input type="file" name="bukti_foto" class="form-control py-2 shadow-sm @error('bukti_foto') is-invalid @enderror" accept="image/*" required>
                        @error('bukti_foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-muted">Tanggal Bayar</label>
                        <input type="date" name="tanggal" class="form-control py-2 shadow-sm" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <button type="submit" class="btn btn-pink-biru w-100 py-2 mt-2 fw-bold shadow">
                        Kirim Konfirmasi <i class="bi bi-send-fill ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection