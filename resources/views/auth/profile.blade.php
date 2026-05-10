@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        max-width: 750px; /* Diperkecil dari 1000px */
        margin: 0 auto;
    }

    .profile-card {
        background: white;
        border-radius: 1.5rem; /* Lebih ramping */
        padding: 2rem; /* Padding lebih proporsional */
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
    }

    /* Navigasi Tab Mini */
    .nav-custom-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        background: #f8fafc;
        padding: 0.4rem;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
    }

    .nav-custom-tabs .nav-link {
        flex: 1;
        text-align: center;
        padding: 0.6rem;
        border-radius: 0.8rem;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem; /* Font lebih kecil */
        transition: 0.3s;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .nav-custom-tabs .nav-link.active {
        background: white;
        color: #3b82f6;
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
    }

    /* Header Ringkas */
    .profile-header {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 2rem;
    }

    .avatar-wrapper {
        position: relative;
        width: 90px;
        height: 90px;
        border-radius: 1.5rem;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #334155;
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }

    .avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-initial {
        font-size: 2rem;
        color: #334155;
    }

    .avatar-overlay {
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255,255,255,0.95);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .avatar-overlay:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
    }

    .avatar-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.75rem;
    }

    .avatar-action-button {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.75rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #334155;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .avatar-action-button i {
        font-size: 0.9rem;
    }

    .avatar-hidden-input {
        display: none;
    }

    .profile-actions {
        display: none;
    }

    /* Form Styling Ramping */
    .form-label-custom {
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
        font-size: 0.85rem;
        display: block;
    }

    .form-control-custom {
        padding: 0.65rem 1rem;
        border-radius: 0.8rem;
        border: 1.5px solid #f1f5f9;
        background: #f8fafc;
        transition: 0.3s;
        font-size: 0.9rem;
        width: 100%;
    }

    .form-control-custom:focus {
        background: white;
        border-color: #3b82f6;
        outline: none;
    }

    .btn-save {
        padding: 0.65rem 2rem;
        border-radius: 0.8rem;
        font-weight: 700;
        font-size: 0.9rem;
        background: #3b82f6;
        color: white;
        border: none;
        transition: 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.2);
    }

    .tab-pane {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="container py-4">
    <div class="profile-container">

        <div class="profile-card">
            <!-- Header Ringkas -->
            <div class="profile-header">
                    <div class="avatar-wrapper">
                        <img id="avatarPreview" src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : '' }}" alt="Avatar" class="avatar-image {{ auth()->user()->profile_image ? '' : 'd-none' }}">
                        <span id="avatarInitial" class="avatar-initial {{ auth()->user()->profile_image ? 'd-none' : '' }}">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        <label for="profile_image" class="avatar-overlay" title="Ganti foto profil">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <div>
                        <h4 class="fw-bold m-0" style="color: #1e293b;">{{ auth()->user()->name }}</h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small" style="font-size: 0.8rem;">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="avatar-actions">
                            @if(auth()->user()->profile_image)
                                <form action="{{ route('profile.update') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="delete_image">
                                    <button type="submit" class="avatar-action-button" title="Hapus foto profil">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            <!-- Tab Navigation -->
            <nav class="nav nav-custom-tabs" role="tablist">
                <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#info-pane" type="button" role="tab">
                    <i class="fas fa-user"></i> Profil
                </button>
                <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security-pane" type="button" role="tab">
                    <i class="fas fa-shield-alt"></i> Keamanan
                </button>
            </nav>

            <div class="tab-content">
                <!-- FORM PROFIL -->
                <div class="tab-pane fade show active" id="info-pane" role="tabpanel">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="profile">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="avatar-hidden-input">
                        @error('profile_image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control-custom" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control-custom" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Nomor WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control-custom">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-save w-100">Update Profil</button>
                        </div>
                    </form>
                </div>

                <!-- FORM KEAMANAN -->
                <div class="tab-pane fade" id="security-pane" role="tabpanel">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="password">

                        <div class="mb-3">
                            <label class="form-label-custom">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control-custom @error('current_password') is-invalid @enderror" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Password Baru</label>
                            <input type="password" name="password" class="form-control-custom @error('password') is-invalid @enderror" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control-custom" required>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-save w-100">Ganti Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profileInput = document.getElementById('profile_image');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarInitial = document.getElementById('avatarInitial');

        if (profileInput) {
            profileInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (avatarPreview) {
                        avatarPreview.src = e.target.result;
                        avatarPreview.classList.remove('d-none');
                    }
                    if (avatarInitial) {
                        avatarInitial.classList.add('d-none');
                    }
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endsection