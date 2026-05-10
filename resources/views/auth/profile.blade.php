@extends('layouts.app')

@section('content')
<style>
    .profile-section-card {
        background: white;
        border-radius: 2rem;
        padding: 2.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
    }
    .profile-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    .profile-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 2rem;
    }
    .form-label-custom {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
        margin-bottom: 0.5rem;
    }
    .form-control-custom {
        width: 100%;
        padding: 0.625rem 1rem;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        color: #1e293b;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control-custom:focus {
        background-color: white;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    .btn-save-custom {
        background-color: #3b82f6;
        color: white;
        font-weight: 700;
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
        font-size: 0.875rem;
    }
    .btn-save-custom:hover {
        background-color: #2563eb;
    }
</style>

<div class="container py-5" style="max-width: 900px;">
    
    <!-- Profile Information Card -->
    <div class="profile-section-card">
        <div class="mb-4">
            <h2 class="profile-title">Profile Information</h2>
            <p class="profile-subtitle">Update your account's profile information and email address.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="profile">

            <div class="row g-4" style="max-width: 500px;">
                <div class="col-12">
                    <label class="form-label-custom">Nama</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="form-control-custom @error('name') is-invalid @enderror">
                    @error('name')
                        <p class="mt-1 text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="form-control-custom @error('email') is-invalid @enderror">
                    @error('email')
                        <p class="mt-1 text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label-custom">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                           class="form-control-custom @error('phone') is-invalid @enderror">
                    @error('phone')
                        <p class="mt-1 text-danger small">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-save-custom">
                    Save
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password Card -->
    <div class="profile-section-card">
        <div class="mb-4">
            <h2 class="profile-title">Update Password</h2>
            <p class="profile-subtitle">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="password">

            <div class="row g-4" style="max-width: 500px;">
                <div class="col-12">
                    <label class="form-label-custom">Current Password</label>
                    <input type="password" name="current_password" required
                           class="form-control-custom @error('current_password') is-invalid @enderror">
                    @error('current_password')
                        <p class="mt-1 text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label-custom">New Password</label>
                    <input type="password" name="password" required
                           class="form-control-custom @error('password') is-invalid @enderror">
                    @error('password')
                        <p class="mt-1 text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="form-control-custom">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-save-custom">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
