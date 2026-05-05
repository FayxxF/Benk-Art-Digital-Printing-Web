@extends('layouts.app')

@section('content')
<style>
    .auth-card {
        max-width: 450px;
        width: 100%;
        background: white;
        border: 1px solid #f1f3f5;
        border-radius: 2.5rem;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
    .auth-input {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 1.25rem;
        padding: 1rem 1.5rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .auth-input:focus {
        background-color: white;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
    .btn-auth {
        border-radius: 1.25rem;
        padding: 1rem;
        font-weight: bold;
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.15);
    }
</style>

<div class="d-flex align-items-center justify-content-center py-5" style="min-height: 70vh;">
    <div class="auth-card">
        <div class="text-center mb-5">
            <h2 class="fw-black text-dark mb-2">Login Pelanggan</h2>
            <p class="text-muted">Masuk ke akun Benk Art Anda</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="needs-validation">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-bold text-dark small ms-2">Email Address</label>
                <input type="email" name="email" class="form-control auth-input @error('email') is-invalid @enderror" 
                       placeholder="Masukkan Email Anda" required value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback ms-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark small ms-2">Password</label>
                <input type="password" name="password" class="form-control auth-input @error('password') is-invalid @enderror" 
                       placeholder="••••••••" required>
                @error('password')
                    <div class="invalid-feedback ms-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-check ms-2">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label text-muted small" for="remember">Ingat Saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-auth mb-4">
                Masuk
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted small mb-0">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar disini</a>
            </p>
        </div>
    </div>
</div>
@endsection