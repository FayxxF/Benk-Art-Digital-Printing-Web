ini coba tentang kami

@extends('layouts.app')

@section('content')
<style>
    /* Tidied Up Version of the Original About Design - Normal Size */
    .about-hero {
        height: 320px;
        background: linear-gradient(rgba(11, 26, 51, 0.5), rgba(11, 26, 51, 0.5)), url('https://picsum.photos/seed/about-hero/1920/1080');
        background-size: cover;
        background-position: center;
        border-radius: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-bottom: 3.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .section-tag {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--primary-color);
        display: block;
        margin-bottom: 0.75rem;
    }
    
    .about-title {
        font-weight: 800;
        color: #0B1A33;
        line-height: 1.2;
        margin-bottom: 1.25rem;
    }
    
    .about-text {
        color: #718096;
        font-size: 0.95rem;
        line-height: 1.7;
        font-weight: 500;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #A0AEC0;
    }
    
    .story-img-container {
        position: relative;
    }
    
    .story-img {
        width: 80%;
        border-radius: 1.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        display: block;
        margin: 0 auto;
    }
    
    .vision-card {
        background: white;
        padding: 2.5rem;
        border-radius: 2rem;
        border: 1px solid #edf2f7;
        height: 100%;
        transition: 0.3s;
    }
    
    .vision-card:hover {
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
        transform: translateY(-5px);
    }
    
    .mission-card {
        background: #0B1A33;
        color: white;
        padding: 2.5rem;
        border-radius: 2rem;
        height: 100%;
    }
    
    .icon-box {
        width: 50px;
        height: 50px;
        background: #f0f7ff;
        color: var(--primary-color);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }
    
    .check-icon {
        width: 18px;
        height: 18px;
        background: rgba(13, 110, 253, 0.2);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.45rem;
    }

    .cta-banner {
        background: var(--primary-color);
        border-radius: 2.5rem;
        padding: 4rem 2rem;
        text-align: center;
        color: white;
        margin-top: 5rem;
        margin-bottom: 2rem;
    }
</style>

<div class="pb-5">
    <!-- HERO SECTION -->
    <div class="about-hero shadow-sm">
        <div class="px-3">
            <span class="text-info font-weight-bold text-uppercase" style="letter-spacing: 0.3em; font-size: 0.6rem;">IDENTITY</span>
            <h1 class="h2 fw-black mt-2 mb-3 text-white">Tentang <span style="color: #60a5fa;">Benk Art.</span></h1>
            <p class="small opacity-75 max-w-xl mx-auto fw-medium" style="max-width: 450px; margin: 0 auto;">Mitra terpercaya untuk solusi cetak digital berkualitas tinggi sejak 2015.</p>
        </div>
    </div>

    <!-- NARRATIVE SECTION -->
    <div class="row align-items-center mb-5 pb-4">
        <div class="col-lg-6 pr-lg-5 mb-4 mb-lg-0">
            <span class="section-tag">SINCE 2015</span>
            <h2 class="about-title h2">Kisah Perjalanan <br><span class="text-primary">Kreativitas Kami.</span></h2>
            <div class="about-text mb-4">
                <p>Berawal dari workshop kecil di tahun 2015, Benk Art Digital Printing tumbuh menjadi mitra terpercaya untuk kebutuhan cetak bisnis dan personal di seluruh Indonesia.</p>
                <p>Dengan teknologi terbaru dan tim profesional, kami menghadirkan hasil cetak yang tajam, tahan lama, dan berkualitas tinggi yang telah dipercaya oleh ratusan klien.</p>
            </div>
            
            <div class="row pt-4 border-top">
                <div class="col-6">
                    <div class="stat-number">8+</div>
                    <div class="stat-label">Thn Pengalaman</div>
                </div>
                <div class="col-6">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Kepuasan Klien</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 text-center text-lg-start">
            <div class="story-img-container d-inline-block position-relative" style="width: 85%;">
                <img src="https://picsum.photos/seed/story-modern/800/600" class="story-img" alt="Our Story" style="width: 100%; height: 250px !important; object-fit: cover; margin: 0;">
                
                {{-- Quote Card inside the frame --}}
                <div class="quote-card shadow-lg d-none d-md-block" style="position: absolute; bottom: -20px; left: -20px; background: white; padding: 1.5rem; border-radius: 1rem; max-width: 220px; text-align: left; z-index: 10; border: 1px solid rgba(0,0,0,0.05);">
                    <i class="fas fa-quote-left text-primary mb-2 d-block" style="font-size: 0.8rem;"></i>
                    <p class="mb-0 fw-bold italic text-dark" style="font-size: 0.75rem; line-height: 1.5;">"Kualitas bukan sekadar janji, tapi adalah identitas utama kami."</p>
                </div>
            </div>
        </div>
    </div>

    <!-- VISION & MISSION -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="vision-card">
                <div class="icon-box">
                    <i class="fas fa-eye"></i>
                </div>
                <h4 class="fw-black mb-3">Visi Kami</h4>
                <p class="about-text mb-0">Menjadi percetakan digital terdepan dengan solusi kreatif dan kualitas terbaik di Indonesia.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mission-card">
                <div class="icon-box bg-primary text-white">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h4 class="fw-black mb-3">Misi Kami</h4>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center gap-2 mb-2">
                        <div class="check-icon"><i class="fas fa-check"></i></div>
                        <small class="text-white text-opacity-75">Teknologi cetak terbaru.</small>
                    </li>
                    <li class="d-flex align-items-center gap-2 mb-2">
                        <div class="check-icon"><i class="fas fa-check"></i></div>
                        <small class="text-white text-opacity-75">Kualitas premium & presisi.</small>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <div class="check-icon"><i class="fas fa-check"></i></div>
                        <small class="text-white text-opacity-75">Ketepatan waktu pengerjaan.</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- CTA BANNER -->
    <div class="cta-banner shadow-sm">
        <h3 class="fw-black mb-4">Siap mewujudkan ide kreatif Anda?</h3>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('products.index') }}" class="btn btn-light rounded-4 px-4 py-2 fw-bold text-primary shadow-sm">
                Mulai Belanja
            </a>
            <a href="https://wa.me/6289637506893" target="_blank" class="btn btn-outline-light rounded-4 px-4 py-2 fw-bold">
                Konsultasi Gratis
            </a>
        </div>
    </div>
</div>

@endsection