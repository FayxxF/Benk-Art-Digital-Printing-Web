@extends('layouts.app')

@section('content')
<style>
    /* Custom Design System for Tentang Page */
    .about-hero {
        height: 450px;
        background: linear-gradient(rgba(11, 26, 51, 0.6), rgba(11, 26, 51, 0.6)), url('https://picsum.photos/seed/about-hero/1920/1080');
        background-size: cover;
        background-position: center;
        border-radius: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-bottom: 5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }
    
    .section-tag {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3em;
        color: var(--primary-color);
        display: block;
        margin-bottom: 1rem;
    }
    
    .about-title {
        font-weight: 800;
        color: #0B1A33;
        line-height: 1.2;
        margin-bottom: 1.5rem;
    }
    
    .about-text {
        color: #718096;
        font-size: 1rem;
        line-height: 1.8;
        font-weight: 500;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #A0AEC0;
    }
    
    .story-img-container {
        position: relative;
        padding: 1rem;
    }
    
    .story-img {
        width: 100%;
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        transform: rotate(2deg);
        transition: transform 0.5s ease;
    }
    
    .story-img:hover {
        transform: rotate(0deg);
    }
    
    .quote-card {
        position: absolute;
        bottom: -20px;
        left: -20px;
        background: white;
        padding: 2rem;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 250px;
        z-index: 10;
        display: none;
    }
    @media (min-width: 768px) { .quote-card { display: block; } }
    
    .vision-card {
        background: white;
        padding: 3rem;
        border-radius: 2rem;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .vision-card:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        transform: translateY(-5px);
    }
    
    .icon-box {
        width: 60px;
        height: 60px;
        background: #f0f7ff;
        color: var(--primary-color);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        font-size: 1.25rem;
    }
    
    .mission-card {
        background: #0B1A33;
        color: white;
        padding: 3rem;
        border-radius: 2rem;
        height: 100%;
    }
    
    .mission-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .mission-list li {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .check-icon {
        width: 20px;
        height: 20px;
        background: rgba(13, 110, 253, 0.2);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.5rem;
    }
    
    .cta-banner {
        background: var(--primary-color);
        border-radius: 3rem;
        padding: 5rem 2rem;
        text-align: center;
        color: white;
        margin: 6rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .cta-btn-white {
        background: white;
        color: var(--primary-color);
        padding: 1rem 2.5rem;
        border-radius: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
    }
    
    .cta-btn-white:hover {
        background: #f8fafc;
        transform: translateY(-3px);
    }
    
    .cta-btn-outline {
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
        background: rgba(255,255,255,0.1);
    }
</style>

<div class="container mt-5 pt-5">
    <!-- HERO SECTION -->
    <div class="about-hero shadow-lg">
        <div class="px-3">
            <span class="text-info font-weight-bold text-uppercase" style="letter-spacing: 0.4em; font-size: 0.65rem;">IDENTITY</span>
            <h1 class="display-3 fw-black mt-3 mb-4">Tentang <span style="color: #60a5fa;">Benk Art.</span></h1>
            <p class="lead opacity-75 max-w-xl mx-auto fw-medium" style="max-width: 500px; margin: 0 auto;">Mitra terpercaya untuk solusi cetak digital berkualitas tinggi sejak 2015.</p>
        </div>
    </div>

    <!-- NARRATIVE SECTION -->
    <div class="row align-items-center mb-5 pb-5">
        <div class="col-lg-6 pr-lg-5 mb-5 mb-lg-0">
            <span class="section-tag">SINCE 2015</span>
            <h2 class="about-title h1">Kisah Perjalanan <br><span class="text-primary">Kreativitas Kami.</span></h2>
            <div class="about-text mb-5">
                <p>Berawal dari workshop kecil di tahun 2015, Benk Art Digital Printing tumbuh menjadi mitra terpercaya untuk kebutuhan cetak bisnis dan personal di seluruh Indonesia.</p>
                <p>Dengan teknologi terbaru dan tim profesional, kami menghadirkan hasil cetak yang tajam, tahan lama, dan berkualitas tinggi yang telah dipercaya oleh ratusan klien korporat maupun UMKM.</p>
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
        <div class="col-lg-6">
            <div class="story-img-container">
                <img src="https://picsum.photos/seed/story-modern/800/1000" class="story-img" alt="Our Story">
                <div class="quote-card shadow-lg">
                    <i class="fas fa-quote-left text-primary mb-3 h4 d-block"></i>
                    <p class="mb-0 fw-bold italic text-dark" style="font-size: 0.85rem; line-height: 1.6;">"Kualitas bukan sekadar janji, tapi adalah identitas utama kami."</p>
                </div>
            </div>
        </div>
    </div>

    <!-- VISION & MISSION -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="vision-card">
                <div class="icon-box">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="fw-black mb-4">Visi Kami</h3>
                <p class="about-text">Menjadi percetakan digital terdepan dengan solusi kreatif dan kualitas terbaik di Indonesia.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mission-card">
                <div class="icon-box bg-primary text-white">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="fw-black mb-4">Misi Kami</h3>
                <ul class="mission-list">
                    <li>
                        <div class="check-icon"><i class="fas fa-check"></i></div>
                        Teknologi cetak terbaru.
                    </li>
                    <li>
                        <div class="check-icon"><i class="fas fa-check"></i></div>
                        Kualitas premium & presisi.
                    </li>
                    <li>
                        <div class="check-icon"><i class="fas fa-check"></i></div>
                        Ketepatan waktu pengerjaan.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection