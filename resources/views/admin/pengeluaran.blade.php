@extends('layouts.app')

@section('content')
<style>
    /* Definisi warna tema agar class-mu berfungsi */
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
    }
    .text-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: bold;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Riwayat <span class="text-pink-biru">Pengeluaran</span></h2>
            <p class="text-muted small">Daftar semua penggunaan uang kas kelas</p>
        </div>
        <a href="{{ route('transaksi.pengeluaran.create') }}" class="btn btn-pink-biru shadow-sm px-4">
            <i class="bi bi-plus-circle me-2"></i>Catat Pengeluaran Baru
        </a>
    </div>

    {{-- Section Filter --}}
    <div class="card mb-4 border-0 shadow-sm card-custom overflow-hidden">
        <div class="card-body bg-light">
            <form action="{{ route('transaksi.pengeluaran.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Mulai Tanggal</label>
                    <input type="date" name="start_date" class="form-control border-0 shadow-sm" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control border-0 shadow-sm" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary shadow-sm flex-grow-1">
                        <i class="bi bi-filter me-1"></i> Filter Data
                    </button>
                    <a href="{{ route('transaksi.pengeluaran.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Total --}}
    @if($startDate && $endDate)
    <div class="alert bg-white border-0 shadow-sm mb-4 border-start border-4 border-info d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-info-circle-fill text-info me-2"></i>
            Periode: <strong>{{ date('d M Y', strtotime($startDate)) }}</strong> - <strong>{{ date('d M Y', strtotime($endDate)) }}</strong>
        </div>
        <div class="fs-5">
            Total: <strong class="text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong>
        </div>
    </div>
    @endif

    {{-- Tabel Data --}}
    <div class="card card-custom shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-gradient-pink-biru text-white">
                        <tr>
                            <th class="ps-4 py-3 border-0">Tanggal</th>
                            <th class="py-3 border-0">Keterangan</th>
                            <th class="py-3 border-0">Nominal</th>
                            <th class="py-3 text-center border-0">Bukti</th>
                            <th class="py-3 text-center border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluaran as $item)
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $item->keterangan }}</span>
                            </td>
                            <td>
                                <span class="text-danger fw-bold">
                                    - Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                           <td>
                        @if($item->bukti_foto)
                            <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="bi bi-image"></i> Lihat Nota
                            </a>
                        @else
                            <span class="text-muted small italic">Tanpa Bukti</span>
                        @endif
                    </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="return confirm('Hapus data ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted opacity-50"></i>
                                <span class="text-muted">Belum ada riwayat pengeluaran.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection