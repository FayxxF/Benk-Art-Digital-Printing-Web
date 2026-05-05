@extends('layouts.app')

@section('content')
<style>
    .auth-card {
        max-width: 550px;
        width: 100%;
        background: white;
        border: 1px solid #f1f3f5;
        border-radius: 1.5rem;
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

<div class="d-flex align-items-center justify-content-center py-2" style="min-height: 80vh;">
    <div class="auth-card">
        <div class="text-center mb-5">
            <h2 class="fw-black text-dark mb-2">Daftar Akun</h2>
            <p class="text-muted">Mulai pengalaman cetak digital Anda</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="row g-2">
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold text-dark small ms-2">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control auth-input @error('name') is-invalid @enderror" 
                           placeholder="Masukkan Nama Lengkap" required value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback ms-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label fw-bold text-dark small ms-2">Email</label>
                    <input type="email" name="email" class="form-control auth-input @error('email') is-invalid @enderror" 
                           placeholder="email@example.com" required value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback ms-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label fw-bold text-dark small ms-2">Nomor WhatsApp</label>
                    <input type="text" name="phone" class="form-control auth-input @error('phone') is-invalid @enderror" 
                           placeholder="0812xxxx" required value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback ms-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small ms-2">Password</label>
                    <input type="password" name="password" class="form-control auth-input @error('password') is-invalid @enderror" 
                           placeholder="••••••••" required>
                    @error('password')
                        <div class="invalid-feedback ms-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small ms-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control auth-input" 
                           placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-auth my-4">
                Daftar Sekarang
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted small mb-0">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk Disini</a>
            </p>
        </div>
    </div>
</div>
@endsection