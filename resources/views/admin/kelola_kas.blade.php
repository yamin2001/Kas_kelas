@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header Laporan --}}
    <div class="card card-custom shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-journal-check me-2 text-gradient"></i> LAPORAN KAS SISWA
                    </h4>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-calendar3 me-1"></i>
                        Periode: {{ $bulan == 'all' ? 'Tahun '.$tahun : date('F', mktime(0, 0, 0, $bulan, 1)).' '.$tahun }}
                    </p>
                </div>
                
                <form action="{{ route('admin.kelola_kas') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group input-group-sm shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-filter text-primary"></i></span>
                        <select name="bulan" class="form-select border-start-0 ps-0 fw-semibold" onchange="this.form.submit()">
                            <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @for($m=1; $m<=12; ++$m)
                                <option value="{{ sprintf("%02d", $m) }}" {{ $bulan == sprintf("%02d", $m) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <select name="tahun" class="form-select form-select-sm shadow-sm fw-semibold" onchange="this.form.submit()">
                        @for($i = date('Y') - 1; $i <= date('Y') + 2; $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card card-custom shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0" style="font-size: 0.85rem;">
                
                @if($bulan == 'all')
                    {{-- TAMPILAN 1 TAHUN --}}
                    <thead class="bg-gradient-pink-biru text-white">
                        <tr>
                            <th width="50" class="py-3 border-0">No</th>
                            <th class="text-start py-3 border-0">Nama Siswa</th>
                            @php $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des']; @endphp
                            @foreach($namaBulan as $bln) <th class="border-0">{{ $bln }}</th> @endforeach
                            <th class="bg-dark bg-opacity-25 border-0">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $index => $s)
                            <tr>
                                <td class="text-muted small">{{ $index + 1 }}</td>
                                <td class="text-start fw-bold text-dark">{{ $s->nama }}</td>
                                @php $totalSetahun = 0; @endphp
                                @for($i = 1; $i <= 12; $i++)
                                    @php
                                        $bayar = $s->transaksi->filter(function($t) use ($i) { 
                                            return date('n', strtotime($t->tanggal)) == $i && $t->status == 'sukses'; 
                                        })->sum('nominal');
                                        $totalSetahun += $bayar;
                                    @endphp
                                    <td class="{{ $bayar > 0 ? 'fw-bold text-primary' : 'text-muted opacity-25' }}">
                                        {{ $bayar > 0 ? number_format($bayar/1000, 0, ',', '.') : '-' }}
                                    </td>
                                @endfor
                                <td class="fw-bold bg-light text-gradient">
                                    Rp {{ number_format($totalSetahun, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                
                @else
                    {{-- TAMPILAN PER BULAN (MINGGUAN) --}}
                    <thead class="bg-gradient-pink-biru text-white">
                        <tr>
                            <th rowspan="2" width="50" class="align-middle border-0">No</th>
                            <th rowspan="2" class="text-start align-middle border-0">Nama Siswa</th>
                            <th colspan="5" class="py-2 border-bottom border-white border-opacity-25 border-0">Pemasukan Minggu Ke-</th>
                            <th rowspan="2" class="align-middle bg-dark bg-opacity-25 border-0">Total Bulan Ini</th>
                        </tr>
                        <tr>
                            <th class="border-0 small">1<br>Tgl 1-7</th>
                            <th class="border-0 small">2<br>Tgl 8-14</th>
                            <th class="border-0 small">3<br>Tgl 15-21</th>
                            <th class="border-0 small">4<br>Tgl 22-28</th>
                            <th class="border-0 small">5<br>Tgl 29-31</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $index => $s)
                            @php
                                $successTrans = $s->transaksi->where('status', 'sukses');
                                $m1 = $successTrans->filter(function($t) { $d = date('j', strtotime($t->tanggal)); return $d >= 1 && $d <= 7; })->sum('nominal');
                                $m2 = $successTrans->filter(function($t) { $d = date('j', strtotime($t->tanggal)); return $d >= 8 && $d <= 14; })->sum('nominal');
                                $m3 = $successTrans->filter(function($t) { $d = date('j', strtotime($t->tanggal)); return $d >= 15 && $d <= 21; })->sum('nominal');
                                $m4 = $successTrans->filter(function($t) { $d = date('j', strtotime($t->tanggal)); return $d >= 22 && $d <= 28; })->sum('nominal');
                                $m5 = $successTrans->filter(function($t) { $d = date('j', strtotime($t->tanggal)); return $d >= 29; })->sum('nominal');
                                $totalBulan = $m1 + $m2 + $m3 + $m4 + $m5;
                            @endphp
                            <tr>
                                <td class="text-muted small">{{ $index + 1 }}</td>
                                <td class="text-start fw-bold text-dark">{{ $s->nama }}</td>
                                <td class="{{ $m1 > 0 ? 'text-primary fw-bold' : 'text-muted opacity-25' }}">{{ $m1 > 0 ? number_format($m1, 0, ',', '.') : '-' }}</td>
                                <td class="{{ $m2 > 0 ? 'text-primary fw-bold' : 'text-muted opacity-25' }}">{{ $m2 > 0 ? number_format($m2, 0, ',', '.') : '-' }}</td>
                                <td class="{{ $m3 > 0 ? 'text-primary fw-bold' : 'text-muted opacity-25' }}">{{ $m3 > 0 ? number_format($m3, 0, ',', '.') : '-' }}</td>
                                <td class="{{ $m4 > 0 ? 'text-primary fw-bold' : 'text-muted opacity-25' }}">{{ $m4 > 0 ? number_format($m4, 0, ',', '.') : '-' }}</td>
                                <td class="{{ $m5 > 0 ? 'text-primary fw-bold' : 'text-muted opacity-25' }}">{{ $m5 > 0 ? number_format($m5, 0, ',', '.') : '-' }}</td>
                                <td class="fw-bold bg-light text-gradient">
                                    Rp {{ number_format($totalBulan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
                
            </table>
        </div>
    </div>
</div>
@endsection