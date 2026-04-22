@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-custom shadow-lg border-0">
            
                <div class="card-header bg-gradient-pink-biru text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-wallet2 me-2"></i>Catat Pengeluaran Kelas
                    </h5>
                </div>
                
                <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                    <form action="{{ route('transaksi.storePengeluaran') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control form-control-lg bg-light border-0 shadow-sm" 
                                   placeholder="Contoh: Foto copy" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Nominal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 shadow-sm fw-bold text-gradient">Rp</span>
                                <input type="number" name="nominal" class="form-control form-control-lg bg-light border-0 shadow-sm" 
                                       placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control form-control-lg bg-light border-0 shadow-sm" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Bukti Nota / Foto</label>
                            <input type="file" name="bukti_foto" class="form-control bg-light border-0 shadow-sm" 
                                   accept="image/*">
                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Format: JPG, PNG (Max 2MB)</small>
                        </div>

                        <div class="d-grid gap-2">
                           
                            <button type="submit" class="btn btn-pink-biru btn-lg shadow-sm">
                                <i class="bi bi-cloud-arrow-up me-2"></i>Simpan Pengeluaran
                            </button>
                            
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg text-muted border-0 shadow-sm">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection