@extends('layouts.app')

@section('content')
<style>
    .text-gradient {
        background: linear-gradient(45deg, #f06292, #4fc3f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .bg-gradient-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7);
    }
    .btn-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7);
        border: none;
        color: white;
    }
    .btn-pink-biru:hover {
        opacity: 0.9;
        color: white;
    }
    .card-custom {
        border-radius: 15px;
        border: none;
    }
    .form-control:focus {
        border-color: #f06292;
        box-shadow: 0 0 0 0.25rem rgba(240, 98, 146, 0.25);
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark">Catat <span class="text-gradient">Pengeluaran</span></h2>
            <p class="text-muted small mb-0">Masukkan detail pengeluaran uang kas kelas</p>
        </div>
        <a href="{{ route('transaksi.pengeluaran.index') }}" class="btn btn-light border shadow-sm text-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom shadow-sm">
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('transaksi.pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control form-control-lg bg-light" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Keterangan / Keperluan <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control bg-light" rows="3" placeholder="Contoh: Beli sapu dan pel untuk kelas" required>{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Nominal Pengeluaran (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light fw-bold border-end-0">Rp</span>
                                <input type="number" name="nominal" class="form-control bg-light border-start-0" placeholder="Contoh: 50000" min="1000" value="{{ old('nominal') }}" required>
                            </div>
                            <div class="form-text text-muted small"><i class="bi bi-info-circle me-1"></i>Masukkan angka saja tanpa titik/koma.</div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark">Bukti Nota / Struk <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="file" name="bukti_foto" class="form-control bg-light" accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text text-muted small">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-pink-biru btn-lg shadow-sm fw-bold">
                                <i class="bi bi-save me-2"></i> Simpan Data Pengeluaran
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection