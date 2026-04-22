@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Pembayaran Kas Siswa</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('siswa.bayar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Pilih Siswa</label>
                <select name="siswa_id" class="form-control" required>
                    <option value="">-- Pilih Nama Siswa --</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nominal (Rp)</label>
                <input type="number" name="nominal" class="form-control" placeholder="Contoh: 5000" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Bayar</label>
                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Bukti Transfer/Pembayaran (Opsional)</label>
                <input type="file" name="bukti_foto" class="form-control" accept="image/*">
            </div>

            <hr>
            <button type="submit" class="btn btn-success w-100">Simpan Pembayaran</button>
            
            <a href="{{ url('/home') }}" class="btn btn-light w-100 mt-2">Batal</a>
        </form>
    </div>
</div>
@endsection