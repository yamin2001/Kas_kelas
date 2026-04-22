<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Siswa;
use Illuminate\Http\Request;
// PENTING: Import Storage agar fitur hapus file berfungsi
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('siswa')->latest()->get();
        return view('transaksi.index', compact('transaksi'));
    }

    public function createPemasukan()
    {
        $siswa = Siswa::all();
        return view('transaksi.pembayaran', compact('siswa'));
    }

    public function createPembayaran()
    {
        $user = auth()->user();
        $siswa = Siswa::where('nama', $user->name)->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('siswa.bayar', compact('siswa'));
    }

    public function storePembayaran(Request $request)
    {
        $request->validate([
            'siswa_id'   => 'required|exists:siswa,id',
            'nominal'    => 'required|numeric|min:5000',
            'tanggal'    => 'required|date',
            'bukti_foto' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        $data = [
            'siswa_id'   => $request->siswa_id,
            'nominal'    => $request->nominal,
            'tanggal'    => $request->tanggal,
            'jenis'      => 'pemasukan',
            'keterangan' => 'Pembayaran Kas Mandiri (Siswa)',
            'status'     => 'pending', 
        ];

        if ($request->hasFile('bukti_foto')) {
            // Menggunakan nama kolom 'bukti_foto' sesuai validasi
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti_transfer', 'public');
        }

        Transaksi::create($data);

        return redirect()->route('siswa.dashboard')->with('success', 'Pembayaran kas berhasil dikirim!');
    }

    public function konfirmasi($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'sukses']);

        return redirect()->back()->with('success', 'Pembayaran siswa telah dikonfirmasi!');
    }

    public function createPengeluaran()
    {
        return view('admin.form_pengeluaran');
    }
    
    public function indexPemasukan(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Transaksi::where('jenis', 'pemasukan')->with('siswa');

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $pemasukan = $query->orderBy('tanggal', 'desc')->get();
        $totalPemasukan = $pemasukan->where('status', 'sukses')->sum('nominal');

        return view('admin.pemasukan', compact('pemasukan', 'startDate', 'endDate', 'totalPemasukan'));
    }

    public function indexPengeluaran(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Transaksi::where('jenis', 'pengeluaran');

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $pengeluaran = $query->orderBy('tanggal', 'desc')->get();
        $totalPengeluaran = $pengeluaran->sum('nominal');

        return view('admin.pengeluaran', compact('pengeluaran', 'startDate', 'endDate', 'totalPengeluaran'));
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'nominal'    => 'required|numeric|min:1000',
            'keterangan' => 'required|string|max:255',
            'tanggal'    => 'required|date',
            'bukti_foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Logika Saldo
        $totalPemasukan = Transaksi::where('jenis', 'pemasukan')->where('status', 'sukses')->sum('nominal');
        $totalPengeluaran = Transaksi::where('jenis', 'pengeluaran')->sum('nominal');
        $saldoSekarang = $totalPemasukan - $totalPengeluaran;

        if ($request->nominal > $saldoSekarang) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Saldo tidak mencukupi! Sisa saldo: Rp ' . number_format($saldoSekarang, 0, ',', '.'));
        }

        $data = [
            'nominal'    => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal'    => $request->tanggal,
            'jenis'      => 'pengeluaran',
            'status'     => 'sukses', 
        ];

        if ($request->hasFile('bukti_foto')) {
            // Pastikan ini sesuai dengan nama kolom di database kamu (bukti atau bukti_foto)
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti_pengeluaran', 'public');
        }

        Transaksi::create($data);

        return redirect()->route('transaksi.pengeluaran.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Hapus file fisik agar tidak memenuhi penyimpanan
        if ($transaksi->bukti && Storage::disk('public')->exists($transaksi->bukti)) {
            Storage::disk('public')->delete($transaksi->bukti);
        }
        
        if ($transaksi->bukti_foto && Storage::disk('public')->exists($transaksi->bukti_foto)) {
            Storage::disk('public')->delete($transaksi->bukti_foto);
        }

        $transaksi->delete();

        return redirect()->back()->with('success', 'Data dan file bukti berhasil dihapus!');
    } 
}