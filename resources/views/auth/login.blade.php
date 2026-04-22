@extends('layouts.app')

@section('content')
<style>
    /* Definisi tema agar konsisten dengan halaman lain */
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
    <div class="col-md-4">
        <div class="card shadow-lg card-custom overflow-hidden">
            <div class="card-header bg-gradient-pink-biru text-white text-center py-3">
                <h4 class="mb-0 fw-bold">Login Bendahara</h4>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm">{{ $errors->first() }}</div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Email</label>
                        <input type="email" name="email" class="form-control shadow-sm" required value="{{ old('email') }}" placeholder="masukkan email...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Password</label>
                        <input type="password" name="password" class="form-control shadow-sm" required placeholder="******">
                    </div>
                    
                    <button type="submit" class="btn btn-pink-biru w-100 py-2 shadow-sm fw-bold">
                        Masuk Sekarang <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-center mb-0 small text-muted">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #f06292;">Bikin dulu yuk</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection