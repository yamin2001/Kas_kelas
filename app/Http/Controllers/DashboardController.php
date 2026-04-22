<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilan Utama untuk Admin/Bendahara
     */
    public function adminIndex()
{
    // 1. Ambil 5 transaksi terbaru (gabungan pemasukan & pengeluaran)
    $riwayatTerbaru = Transaksi::latest('tanggal')
                        ->latest('id')
                        ->take(5)
                        ->get();

    // 2. Ambil khusus pengeluaran terbaru (digunakan di tabel dashboard)
    // Variabel ini harus bernama $pengeluaran sesuai dengan @forelse di Blade kamu
    $pengeluaran = Transaksi::where('jenis', 'pengeluaran')
                    ->latest()
                    ->take(5)
                    ->get();

    // 3. Hitung ringkasan statistik
    $totalPemasukan = Transaksi::where('jenis', 'pemasukan')->where('status', 'sukses')->sum('nominal');
    $totalPengeluaran = Transaksi::where('jenis', 'pengeluaran')->sum('nominal');
    
    $saldo = $totalPemasukan - $totalPengeluaran;

    // PERBAIKAN DI SINI: Ganti nama $pending menjadi $inboxPending
    // Agar sesuai dengan {{ $inboxPending }} di dashboard.blade.php kamu
    $inboxPending = Transaksi::where('status', 'pending')->count();

    return view('admin.dashboard', compact(
        'pengeluaran', 
        'riwayatTerbaru', 
        'saldo', 
        'totalPengeluaran', 
        'inboxPending' // Pastikan dikirim dengan nama ini
    ));
    }

    /**
     * Halaman Kelola Kas Siswa (Tabulasi Pembayaran)
     */
    public function kelolaKas(Request $request)
    {
        $tahun = $request->tahun ?? date('Y'); 
        $bulan = $request->bulan ?? 'all'; 

        // Menggunakan Eager Loading agar tidak berat (N+1 Problem)
        $siswa = Siswa::with(['transaksi' => function($query) use ($tahun, $bulan) {
            $query->where('jenis', 'pemasukan')
                  ->where('status', 'sukses')
                  ->whereYear('tanggal', $tahun);
                  
            if ($bulan != 'all') {
                $query->whereMonth('tanggal', $bulan);
            }
        }])->orderBy('nama', 'asc')->get();

        return view('admin.kelola_kas', compact('siswa', 'tahun', 'bulan'));
    }

    /**
     * Tampilan Utama untuk Siswa
     */
    public function siswaIndex()
    {
        // Ambil profil siswa milik user yang login
        $siswa = Siswa::where('user_id', Auth::id())->first();

        if (!$siswa) {
            // Sebaiknya redirect ke halaman error atau info profil
            return view('errors.profile_missing')->with('message', 'Profil siswa Anda belum terdaftar.');
        }

        $totalBayar = Transaksi::where('siswa_id', $siswa->id)
            ->where('status', 'sukses')
            ->sum('nominal');

        $riwayat = Transaksi::where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.dashboard', compact('siswa', 'totalBayar', 'riwayat'));
    }

    /**
     * Laporan Kas Berdasarkan Rentang Tanggal
     */
    public function laporan(Request $request)
    {
        // Default: bulan ini
        $tglAwal = $request->tgl_awal ?? date('Y-m-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-m-t');

        $transaksi = Transaksi::with('siswa')
            ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
            ->where('status', 'sukses')
            ->orderBy('tanggal', 'asc')
            ->get();

        $pemasukan = $transaksi->where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaran = $transaksi->where('jenis', 'pengeluaran')->sum('nominal');
        $selisih = $pemasukan - $pengeluaran;

        return view('admin.laporan', compact('transaksi', 'tglAwal', 'tglAkhir', 'pemasukan', 'pengeluaran', 'selisih'));
    }
}