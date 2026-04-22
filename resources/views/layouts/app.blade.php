<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Kas - XI RPL 2</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --gradasi-pink-biru: linear-gradient(135deg, #ff9a9e 0%, #c2e9fb 100%);
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        .bg-gradient-pink-biru {
            background: var(--gradasi-pink-biru) !important;
            color: white !important;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .btn-pink-biru {
            background: var(--gradasi-pink-biru);
            border: none;
            color: white;
            font-weight: 600;
        }

        .text-gradient {
            background: var(--gradasi-pink-biru);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .navbar.bg-gradient-pink-biru .nav-link, 
        .navbar.bg-gradient-pink-biru .navbar-brand {
            color: #ffffff !important;
            font-weight: 500;
        }
        
        .nav-link.active {
            font-weight: 700 !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-gradient-pink-biru shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">KAS XI RPL 2</a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    {{-- MENU NAVIGASI ADMIN --}}
                    @if(auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Data Transaksi
                            </a>
                            <ul class="dropdown-menu shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('transaksi.pemasukan.index') }}">Data Pemasukan</a></li>
                                <li><a class="dropdown-item" href="{{ route('transaksi.pengeluaran.index') }}">Data Pengeluaran</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/kelola-kas') ? 'active' : '' }}" href="{{ route('admin.kelola_kas') }}">Kelola Kas</a>
                        </li>

                    {{-- MENU NAVIGASI SISWA --}}
                    @elseif(auth()->user()->role == 'siswa')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('siswa/dashboard') ? 'active' : '' }}" href="{{ route('siswa.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('siswa/bayar') ? 'active' : '' }}" href="{{ route('siswa.bayar') }}">
                                <i class="bi bi-wallet2 me-1"></i> Bayar Kas
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->nama }} 
                            <span class="badge bg-white text-dark ms-1" style="opacity: 0.9;">{{ ucfirst(auth()->user()->role) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<div class="container mb-5">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- SweetAlert Notifikasi --}}
@if(session('success'))
<script>
    Swal.fire({
        title: "Berhasil!",
        text: "{{ session('success') }}",
        icon: "success",
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: "Gagal!",
        text: "{{ session('error') }}",
        icon: "error"
    });
</script>
@endif

</body>
</html>