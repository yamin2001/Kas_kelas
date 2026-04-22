@extends('layouts.app')

@section('content')
<style>
    /* Konsistensi tema Pink-Biru */
    .bg-gradient-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7) !important;
    }
    .btn-pink-biru {
        background: linear-gradient(45deg, #f06292, #4fc3f7);
        color: white;
        border: none;
    }
    .btn-pink-biru:hover {
        opacity: 0.9;
        color: white;
    }
    .card-custom {
        border-radius: 15px;
        border: none;
    }
</style>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-lg card-custom overflow-hidden">
            <div class="card-header bg-gradient-pink-biru text-white text-center py-3">
                <h4 class="mb-0 fw-bold">Daftarin Akun Kamu</h4>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Kamu</label>
                        <input type="text" name="nama" class="form-control shadow-sm" value="{{ old('nama') }}" required placeholder="siapa nama kamu?">
                    </div>
                   
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Email</label>
                        <input type="email" name="email" class="form-control shadow-sm" required value="{{ old('email') }}" placeholder="email@contoh.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Password</label>
                            <input type="password" name="password" class="form-control shadow-sm" required placeholder="******">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Konfirmasi</label>
                            <input type="password" name="password_confirmation" class="form-control shadow-sm" required placeholder="******">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-pink-biru w-100 py-2 shadow-sm fw-bold mt-2">
                        Daftar Sekarang <i class="bi bi-person-plus-fill ms-1"></i>
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-center mb-0 small text-muted">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #f06292;">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection