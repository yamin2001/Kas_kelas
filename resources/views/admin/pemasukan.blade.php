@extends('layouts.app')

@section('content')
<style>
    /* Konsistensi dengan tema Pink-Biru dashboard */
    .text-gradient-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .card-custom {
        border-radius: 15px;
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
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="fw-bold text-dark">Data <span class="text-gradient-pink-biru">Pemasukan</span></h2>
        {{-- Tombol opsional jika admin ingin menambah pemasukan manual --}}
    </div>

    {{-- Form Filter Tanggal --}}
    <div class="card card-custom shadow-sm mb-4 border-0">
        <div class="card-body bg-light rounded">
            <form action="{{ route('transaksi.pemasukan.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small">Mulai Tanggal</label>
                    <input type="date" name="start_date" class="form-control border-0 shadow-sm" value="{{ request('start_date') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control border-0 shadow-sm" value="{{ request('end_date') }}" required>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary shadow-sm flex-grow-1"><i class="bi bi-filter me-2"></i>Filter</button>
                        @if(request('start_date'))
                            <a href="{{ route('transaksi.pemasukan.index') }}" class="btn btn-outline-secondary shadow-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Total --}}
    @if(request('start_date'))
        <div class="alert bg-white shadow-sm border-0 border-start border-4 border-info d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted">Periode:</span> 
                <strong>{{ date('d M Y', strtotime($startDate)) }}</strong> - <strong>{{ date('d M Y', strtotime($endDate)) }}</strong>
            </div>
            <div>
                <span class="text-muted">Total Pemasukan:</span> 
                <strong class="text-success fs-5">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong>
            </div>
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="card card-custom shadow-sm overflow-hidden border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small uppercase">Tanggal</th>
                            <th class="text-muted small uppercase">Keterangan / Nama</th>
                            <th class="text-muted small uppercase">Nominal</th>
                            <th class="text-muted small uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemasukan as $t)
                        <tr>
                            <td class="ps-4 text-muted">{{ date('d M Y', strtotime($t->tanggal)) }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $t->siswa->nama ?? 'Sistem' }}</div>
                                <div class="text-muted small">{{ $t->keterangan }}</div>
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                            <td>
                                @if($t->status == 'sukses')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success px-3">
                                        <i class="bi bi-check-circle me-1"></i>Sukses
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning px-3">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-folder2-open fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted">Tidak ada data ditemukan</span>
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