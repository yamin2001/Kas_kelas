<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\SiswaController; 

// --- 1. RUTE PUBLIK (GUEST) ---
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- RUTE UMUM (LOGOUT) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// --- 2. RUTE KHUSUS ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
    
    // PEMASUKAN
    Route::get('/pemasukan', [TransaksiController::class, 'indexPemasukan'])->name('transaksi.pemasukan.index');
    
    // PENGELUARAN
    Route::get('/pengeluaran', [TransaksiController::class, 'indexPengeluaran'])->name('transaksi.pengeluaran.index'); 
    Route::get('/pengeluaran/create', [TransaksiController::class, 'createPengeluaran'])->name('transaksi.pengeluaran.create');
    Route::post('/pengeluaran', [TransaksiController::class, 'storePengeluaran'])->name('transaksi.pengeluaran.store');
    Route::get('/pengeluaran/{id}/edit', [TransaksiController::class, 'editPengeluaran'])->name('transaksi.pengeluaran.edit');
    Route::put('/pengeluaran/{id}', [TransaksiController::class, 'updatePengeluaran'])->name('transaksi.pengeluaran.update');

    // KELOLA KAS & LAPORAN
    Route::get('/kelola-kas', [DashboardController::class, 'kelolaKas'])->name('admin.kelola_kas');
    Route::post('/konfirmasi/{id}', [TransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('admin.laporan');
    
    // HAPUS TRANSAKSI
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
});

// --- 3. RUTE KHUSUS SISWA ---
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'index'])->name('siswa.dashboard');
    Route::get('/bayar', [TransaksiController::class, 'createPembayaran'])->name('siswa.bayar');
    Route::post('/bayar', [TransaksiController::class, 'storePembayaran'])->name('siswa.bayar.store');
});

Route::get('/home', function () {
    if (auth()->check()) {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role == 'siswa') {
            return redirect()->route('siswa.dashboard');
        }
    }
    return redirect()->route('login');
});