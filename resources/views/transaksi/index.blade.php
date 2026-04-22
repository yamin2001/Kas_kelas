@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Dashboard Bendahara - XI RPL 2</h3>
        <div>
             <a href="{{ route('siswa.bayar') }}" class="btn btn-success">Catat Pembayaran Kas</a>
             <a href="{{ route('transaksi.pengeluaran') }}" class="btn btn-danger">Catat Pengeluaran</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Saldo Kas</h6>
                    <h3>Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">🕰️ Riwayat Transaksi (Pemasukan & Pengeluaran)</div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Keterangan / Nama Siswa</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatTransaksi as $trx)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge {{ $trx->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($trx->jenis) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $trx->siswa->nama ?? 'Umum/Admin' }} - {{ $trx->keterangan }}
                                </td>
                                <td class="fw-bold {{ $trx->jenis == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center p-3">Belum ada data transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">📋 Status Kas Siswa (Target: Rp {{ number_format($targetKas, 0, ',', '.') }})</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $s->nama }}</td>
                                <td>Rp {{ number_format($s->total_bayar ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($s->status_lunas)
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Nunggak (Sisa: Rp {{ number_format($s->kekurangan, 0, ',', '.') }})</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection