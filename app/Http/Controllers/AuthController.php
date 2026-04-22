<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } 
            
            return redirect()->route('siswa.dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal, periksa email/password.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

   public function register(Request $request)
{
    // 1. Validasi input
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // 2. Buat User baru di tabel 'users'
    $user = \App\Models\User::create([
        'name' => $request->nama,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'siswa', // Default role saat daftar adalah siswa
    ]);

    // 3. OTOMATISASI: Buat data di tabel 'siswa'
    // Inilah kuncinya agar tidak perlu ke phpMyAdmin lagi
    \App\Models\Siswa::create([
        'user_id' => $user->id, // Ambil ID dari user yang baru saja dibuat
        'nama'    => $request->nama,
        // Kolom lain bisa dikosongkan dulu atau diisi default
    ]);

    return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
}   
}