ini coba tentang kami

@extends('layouts.app')

@section('content')
<style>
    /* Tidied Up Version of the Original About Design - Normal Size */
    .about-hero {
        height: 320px;
        background: linear-gradient(rgba(11, 26, 51, 0.5), rgba(11, 26, 51, 0.5)), url('{{ asset('storage/asset/tentang-kami.jpg') }}');
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

    /* LOKASI & KONTAK SECTION STYLE */
    .contact-section {
        margin-top: 4rem;
        margin-bottom: 2rem;
    }

    .maps-container {
        border-radius: 2rem;
        overflow: hidden;
        border: 1px solid #edf2f7;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        height: 100%;
        min-height: 380px;
    }

    .contact-info-card {
        background: white;
        padding: 2.5rem;
        border-radius: 2rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .contact-item:last-child {
        margin-bottom: 0;
    }

    .contact-icon-box {
        width: 44px;
        height: 44px;
        background: #f0f7ff;
        color: var(--primary-color);
        border-radius: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .contact-content h6 {
        font-weight: 700;
        color: #0b1a33;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .contact-content p {
        color: #718096;
        font-size: 0.85rem;
        margin-bottom: 0;
        line-height: 1.5;
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
                <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop" class="story-img" alt="Our Story" style="width: 100%; height: 250px !important; object-fit: cover; margin: 0;">
                
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

    <!-- LOKASI & KONTAK SECTION -->
    <section class="contact-section">
        <div class="mb-4">
            <span class="section-tag text-center mx-auto">Kunjungi Kami</span>
            <h2 class="about-title h2 text-center mb-4" style="color: #0b1a33;">Lokasi & Jam Operasional</h2>
        </div>

        <div class="row g-4 align-items-stretch">
            <!-- Kolom Map -->
            <div class="col-lg-7 d-flex flex-column">
                <div class="maps-container flex-grow-1">
                    <iframe 
                        src="https://maps.google.com/maps?q=-6.3511354171669305,107.1199625840855&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <!-- Kolom Kontak & Jam Kerja -->
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <div>
                        <h4 class="fw-black mb-4" style="color: #0b1a33; font-size: 1.25rem;">Hubungi & Temukan Kami</h4>
                        
                        <!-- Alamat -->
                        <div class="contact-item">
                            <div class="contact-icon-box">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-content">
                                <h6>Alamat Workshop</h6>
                                <p>Jl. Kiai H. Raden Ma'mun Nawawi No.88, Sukadami, Cikarang Sel., Kabupaten Bekasi, Jawa Barat 17530</p>
                            </div>
                        </div>

                        <!-- Jam Kerja -->
                        <div class="contact-item">
                            <div class="contact-icon-box" style="background: #ecfdf5; color: #10b981;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-content">
                                <h6>Jam Operasional</h6>
                                <p class="fw-semibold text-dark">Senin - Sabtu: 08.00 - 20.00 WIB</p>
                                <p class="text-danger small fw-semibold">Minggu & Hari Libur: Tutup</p>
                            </div>
                        </div>

                        <!-- Hubungi Kami -->
                        <div class="contact-item">
                            <div class="contact-icon-box" style="background: #fdf2f8; color: #db2777;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-content">
                                <h6>Kontak Langsung</h6>
                                <p>WhatsApp: <a href="https://wa.me/6289637506893" target="_blank" class="text-primary fw-bold text-decoration-none">+62 896-3750-6893</a></p>
                                <p>Email: <a href="mailto:muliaprimabenk@gmail.com" class="text-secondary text-decoration-none">muliaprimabenk@gmail.com</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-top mt-3">
                        <a href="https://wa.me/6289637506893" target="_blank" class="btn btn-primary w-100 rounded-4 py-2 fw-bold text-white shadow-sm">
                            <i class="fab fa-whatsapp me-2"></i> Hubungi via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection