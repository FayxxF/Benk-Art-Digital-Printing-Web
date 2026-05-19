@extends('layouts.app')

@section('content')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

<style>
    /* Style untuk Cropper JS agar berbentuk lingkaran */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }
    
    .cropper-view-box {
        outline: 2px solid #3b82f6;
        outline-color: rgba(59, 130, 246, 0.75);
    }

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

    .avatar-container {
        position: relative;
        width: 90px;
        height: 90px;
    }

    .avatar-wrapper {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: #334155;
        font-weight: 700;
        border: 2px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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
        bottom: -2px;
        right: -2px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #3b82f6;
        color: white;
        border: 2px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.35);
        font-size: 0.85rem;
        z-index: 5;
    }

    .avatar-overlay:hover {
        transform: scale(1.1);
        background: #2563eb;
        box-shadow: 0 6px 14px rgba(59, 130, 246, 0.45);
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
                    <div class="avatar-container">
                        <div class="avatar-wrapper">
                            <img id="avatarPreview" src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : '' }}" alt="Avatar" class="avatar-image {{ auth()->user()->profile_image ? '' : 'd-none' }}">
                            <span id="avatarInitial" class="avatar-initial {{ auth()->user()->profile_image ? 'd-none' : '' }}">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
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
            @php
                $showSecurity = $errors->has('current_password') || $errors->has('password') || old('action') == 'password';
            @endphp

            <!-- Tab Navigation -->
            <nav class="nav nav-custom-tabs" role="tablist">
                <button class="nav-link {{ !$showSecurity ? 'active' : '' }}" id="info-tab" data-bs-toggle="pill" data-bs-target="#info-pane" type="button" role="tab">
                    <i class="fas fa-user"></i> Profil
                </button>
                <button class="nav-link {{ $showSecurity ? 'active' : '' }}" id="security-tab" data-bs-toggle="pill" data-bs-target="#security-pane" type="button" role="tab">
                    <i class="fas fa-shield-alt"></i> Keamanan
                </button>
            </nav>

            <div class="tab-content">
                <!-- FORM PROFIL -->
                <div class="tab-pane fade {{ !$showSecurity ? 'show active' : '' }}" id="info-pane" role="tabpanel">
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
                <div class="tab-pane fade {{ $showSecurity ? 'show active' : '' }}" id="security-pane" role="tabpanel">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="password">

                        <div class="mb-3">
                            <label class="form-label-custom">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control-custom @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="text-danger small mt-1" style="font-weight: 600; font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Password Baru</label>
                            <input type="password" name="password" class="form-control-custom @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="text-danger small mt-1" style="font-weight: 600; font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
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

<!-- Modal Crop Foto Profil -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="modal-title fw-bold" id="cropperModalLabel" style="color: #1e293b; font-size: 1.15rem;">Sesuaikan Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="img-container mb-3" style="max-height: 380px; overflow: hidden; border-radius: 1rem; background: #f8fafc; border: 1px solid #f1f5f9;">
                    <img id="imageToCrop" src="" alt="Source Image" style="max-width: 100%; display: block;">
                </div>
                <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1 text-primary"></i> Geser atau cubit/scroll gambar untuk menyesuaikan posisi di dalam lingkaran.</p>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-light rounded-3 px-4 fw-bold text-muted border-0" data-bs-dismiss="modal" style="font-size: 0.85rem; padding: 0.6rem 1.25rem;">Batal</button>
                <button type="button" id="cropButton" class="btn btn-primary rounded-3 px-4 fw-bold" style="font-size: 0.85rem; padding: 0.6rem 1.25rem;">Potong & Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profileInput = document.getElementById('profile_image');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarInitial = document.getElementById('avatarInitial');
        
        let cropper = null;
        const cropperModalEl = document.getElementById('cropperModal');
        const cropperModal = new bootstrap.Modal(cropperModalEl);
        const imageToCrop = document.getElementById('imageToCrop');
        const cropButton = document.getElementById('cropButton');
        let selectedFile = null;

        if (profileInput) {
            profileInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (!file) return;

                selectedFile = file;

                const reader = new FileReader();
                reader.onload = function (e) {
                    imageToCrop.src = e.target.result;
                    cropperModal.show();
                };
                reader.readAsDataURL(file);
            });
        }

        cropperModalEl.addEventListener('shown.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: false,
                center: true,
                highlight: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false,
                background: false
            });
        });

        cropperModalEl.addEventListener('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            if (profileInput && !avatarPreview.src.startsWith('data:image')) {
                profileInput.value = '';
            }
        });

        cropButton.addEventListener('click', function () {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const croppedDataURL = canvas.toDataURL('image/jpeg', 0.9);
            
            if (avatarPreview) {
                avatarPreview.src = croppedDataURL;
                avatarPreview.classList.remove('d-none');
            }
            if (avatarInitial) {
                avatarInitial.classList.add('d-none');
            }

            canvas.toBlob(function (blob) {
                const croppedFile = new File([blob], selectedFile.name, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                profileInput.files = dataTransfer.files;

                cropperModal.hide();
            }, 'image/jpeg', 0.9);
        });
    });
</script>
@endsection