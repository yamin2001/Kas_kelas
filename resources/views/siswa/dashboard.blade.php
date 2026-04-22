@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header Sapaan --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Halo, <span class="text-gradient">{{ $siswa->nama }}</span>! 👋</h3>
        <p class="text-muted">Selamat datang kembali di dashboard kas kelas XI RPL 2.</p>
    </div>

    <div class="row g-4 mb-5">
        {{-- Card Total Saldo Saya --}}
        <div class="col-md-6">
            <div class="card card-custom bg-gradient-pink-biru border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="bi bi-wallet2 fs-3 text-white"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white text-opacity-75 fw-semibold">Total Pembayaran Saya</p>
                        <h2 class="mb-0 fw-bold text-white">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Status Terakhir (Opsional/Info Tambahan) --}}
        <div class="col-md-6">
            <div class="card card-custom bg-white border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3">
                        <i class="bi bi-clock-history fs-3 text-gradient"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fw-semibold">Transaksi Terakhir</p>
                        <h4 class="mb-0 fw-bold">
                            @if($riwayat->count() > 0)
                                {{ $riwayat->first()->tanggal }}
                            @else
                                Belum ada data
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card card-custom border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bi bi-list-stars me-2 text-primary"></i>Riwayat Pembayaran Saya
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $r)
                    <tr>
                        <td class="ps-4 align-middle">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</td>
                        <td class="align-middle fw-bold">Rp {{ number_format($r->nominal, 0, ',', '.') }}</td>
                        <td class="align-middle">{{ $r->keterangan }}</td>
                        <td class="text-center align-middle">
                            {{-- Kita asumsikan ada kolom status di tabel transaksi Anda --}}
                            <span class="badge rounded-pill {{ $r->status == 'sukses' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} px-3">
                                {{ ucfirst($r->status ?? 'Sukses') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-emoji-frown d-block fs-1 mb-2"></i>
                            Kamu belum memiliki riwayat pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection