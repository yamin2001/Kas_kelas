<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index()
{
    $user = auth()->user();
    
    // 1. Ambil data siswa yang sedang login
    $siswa = \App\Models\Siswa::where('nama', $user->name)->first();

    if (!$siswa) {
        return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
    }

    // 2. Ambil semua riwayat transaksi siswa ini
    $riwayat = \App\Models\Transaksi::where('siswa_id', $siswa->id)
                                    ->orderBy('tanggal', 'desc')
                                    ->get();

    // 3. HITUNG TOTAL: Ambil nominal dari transaksi yang sudah 'sukses'
    $totalBayar = $riwayat->where('status', 'sukses')->sum('nominal');

    // 4. Kirim SEMUA variabel ke view (siswa, riwayat, DAN totalBayar)
    return view('siswa.dashboard', compact('siswa', 'riwayat', 'totalBayar'));
}
}