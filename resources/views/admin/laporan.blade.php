@extends('layouts.app')

@section('content')
<div class="card shadow border-0 mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Filter Laporan Keuangan</h5>
        <form action="{{ route('admin.laporan') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-muted small">Dari Tanggal</label>
                <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4 text-center">
    <div class="col-md-4">
        <div class="card bg-success text-white border-0 shadow-sm"><div class="card-body">
            <h6>Total Pemasukan</h6>
            <h4 class="fw-bold">Rp {{ number_format($pemasukan, 0, ',', '.') }}</h4>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white border-0 shadow-sm"><div class="card-body">
            <h6>Total Pengeluaran</h6>
            <h4 class="fw-bold">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h4>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white border-0 shadow-sm"><div class="card-body">
            <h6>Selisih (Saldo Periode)</h6>
            <h4 class="fw-bold">Rp {{ number_format($selisih, 0, ',', '.') }}</h4>
        </div></div>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr>
                        <td>{{ date('d M Y', strtotime($t->tanggal)) }}</td>
                        <td>
                            <span class="badge {{ $t->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($t->jenis) }}
                            </span>
                        </td>
                        <td>{{ $t->siswa->nama ?? '' }} {{ $t->siswa ? '-' : '' }} {{ $t->keterangan }}</td>
                        <td class="text-end text-success fw-semibold">
                            {{ $t->jenis == 'pemasukan' ? 'Rp '.number_format($t->nominal, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-end text-danger fw-semibold">
                            {{ $t->jenis == 'pengeluaran' ? 'Rp '.number_format($t->nominal, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3">Tidak ada transaksi di rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection