@extends('layouts.app')

@section('content')
<style>
    /* CSS Anti-Jitter & Custom Styles */
    body.modal-open {
        padding-right: 0 !important;
        overflow: hidden;
    }
    
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
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="fw-bold text-dark">Dashboard <span class="text-gradient">Bendahara</span></h2>
        
        <a href="{{ route('transaksi.pengeluaran.index') }}" class="btn btn-pink-biru">
            <i class="bi bi-plus-circle"></i> Catat Pengeluaran
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card card-custom bg-gradient-pink-biru shadow-lg">
                <div class="card-body p-4 text-white">
                    <h6 class="text-white opacity-75 text-uppercase small fw-bold">Total Saldo Kas</h6>
                    <h2 class="fw-bold mb-0">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom bg-white shadow-sm border-start border-4 border-warning">
                <div class="card-body p-4">
                    <h6 class="text-muted text-uppercase small fw-bold">Inbox Pending</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ $inboxPending }} <span class="fs-5 fw-normal">Transaksi</span></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom bg-white shadow-sm border-start border-4 border-danger">
                <div class="card-body p-4">
                    <h6 class="text-muted text-uppercase small fw-bold">Total Pengeluaran</h6>
                    <h2 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-gradient"></i>Riwayat Transaksi Terbaru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Jenis</th>
                            <th>Keterangan / Nama</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DISINKRONKAN: Menggunakan $riwayatTerbaru sesuai Controller --}}
                        @forelse($riwayatTerbaru as $t)
                        <tr>
                            <td class="ps-4 text-muted">{{ date('d M Y', strtotime($t->tanggal)) }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $t->jenis == 'pemasukan' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3">
                                    {{ ucfirst($t->jenis) }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $t->siswa->nama ?? 'Sistem' }}</div>
                                <div class="text-muted small">{{ $t->keterangan }}</div>
                                
                                @if($t->bukti_foto)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $t->id }}" class="text-decoration-none small fw-bold text-gradient">
                                        <i class="bi bi-image"></i> Lihat Bukti
                                    </a>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                            <td>
                                @if($t->status == 'sukses')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Sukses</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($t->status == 'pending')
                                    <form action="{{ route('transaksi.konfirmasi', $t->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-pink-biru shadow-sm">Konfirmasi</button>
                                    </form>
                                @else
                                    <span class="text-muted small">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- AREA MODAL --}}
@foreach($riwayatTerbaru as $t)
    @if($t->bukti_foto)
    <div class="modal fade" id="modalBukti{{ $t->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Bukti Transaksi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img src="{{ asset('storage/' . $t->bukti_foto) }}" 
                         class="img-fluid rounded shadow-sm" 
                         alt="Bukti Foto"
                         style="max-height: 70vh; width: 100%; object-fit: contain;">
                    
                    <div class="mt-3 p-3 bg-light rounded-3 text-start">
                        <div class="small text-muted mb-1">Detail Transaksi:</div>
                        <div class="fw-bold text-dark">{{ $t->siswa->nama ?? 'Sistem' }}</div>
                        <div class="text-primary fw-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <a href="{{ asset('storage/' . $t->bukti_foto) }}" target="_blank" class="btn btn-sm btn-light border w-100 mb-2">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Ukuran Penuh
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection