@extends('layouts.app')

@section('content')
<style>
    /* Premium Design System */
    .hero-section {
        background: #f8fafc;
        border-radius: 2.5rem;
        overflow: hidden;
        position: relative;
        padding: 1.5rem 2.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #ffffff;
        box-shadow: 0 15px 30px rgba(0,0,0,0.02);
    }
    
    .hero-skew {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: rgba(13, 110, 253, 0.04);
        transform: skewX(-12deg) translateX(25%);
        z-index: 1;
    }

    .badge-premium {
        background: white;
        padding: 0.6rem 1.25rem;
        border-radius: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid #edf2f7;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .dot-animate {
        width: 8px;
        height: 8px;
        background: #0d6efd;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }

    .display-title {
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.02em;
        color: #1a202c;
    }

    .service-card {
        background: white;
        padding: 1.75rem;
        border-radius: 2rem;
        border: 1px solid #f1f5f9;
        transition: all 0.4s ease;
        text-align: center;
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(13, 110, 253, 0.1);
        border-color: #e2e8f0;
    }

    .service-icon {
        width: 64px;
        height: 64px;
        background: #f0f7ff;
        color: #0d6efd;
        border-radius: 1.25rem;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.5rem;
        transition: 0.4s;
    }

    .service-card:hover .service-icon {
        background: #0d6efd;
        color: white;
    }

    .product-card-premium {
        background: white;
        border-radius: 2.5rem;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.4s ease;
        padding: 0.75rem;
        height: 100%;
    }

    .product-card-premium:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(13, 110, 253, 0.08);
    }

    .product-img-container {
        height: 200px;
        border-radius: 1.5rem;
        overflow: hidden;
        position: relative;
    }

    .product-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.7s ease;
    }

    .product-card-premium:hover img {
        transform: scale(1.1);
    }

    .floating-badge {
        background: white;
        padding: 1rem;
        border-radius: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        position: absolute;
        z-index: 10;
    }

    .hero-img-wrapper {
        background: white;
        padding: 0.75rem;
        border-radius: 2.5rem;
        box-shadow: 0 20px 40px -12px rgba(0,0,0,0.1);
        transform: rotate(2deg);
        transition: 0.7s;
        max-width: 380px;
        margin-left: auto;
    }

    .hero-img-wrapper:hover {
        transform: rotate(0deg);
    }
</style>

<div class="pb-5">
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-skew d-none d-lg-block"></div>
        
        <div class="row align-items-center position-relative" style="z-index: 10;">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="badge-premium">
                    <div class="dot-animate"></div>
                    <small class="fw-bold text-uppercase tracking-wider" style="font-size: 10px;">Premium Printing Solution</small>
                </div>
                
                <h1 class="h1 display-title mb-3">
                    Cetak Digital <br>
                    <span class="text-primary">Tanpa Batas.</span>
                </h1>
                
                <p class="text-muted mb-4 fs-6 pr-lg-5">
                    Solusi cetak profesional dengan hasil warna yang memukau untuk mendukung pertumbuhan bisnis dan ide kreatif Anda.
                </p>
                
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('products.index') }}" class="btn btn-primary rounded-4 px-4 py-2 fw-bold shadow-lg text-white">
                        Mulai Belanja <i class="fas fa-arrow-right ms-2 small"></i>
                    </a>
                    <!-- <a href="#" class="btn btn-outline-secondary rounded-4 px-4 py-2 fw-bold border-2">
                        Tentang Kami
                    </a> -->
                </div>
                
                <div class="d-flex gap-5 pt-4 border-top">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">10k+</h4>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 9px; letter-spacing: 1px;">Pelanggan</small>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">50+</h4>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 9px; letter-spacing: 1px;">Produk</small>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">24h</h4>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 9px; letter-spacing: 1px;">Pengerjaan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block position-relative">
                <div class="hero-img-wrapper">
                    <img src="https://picsum.photos/seed/print-hero/800/800" alt="Hero Image" class="img-fluid rounded-4">
                    
                    <!-- Floating Badges -->
                    <div class="floating-badge" style="top: 20%; left: -40px; animation: float 4s ease-in-out infinite;">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold small">Hasil Presisi</p>
                            <p class="mb-0 text-muted" style="font-size: 10px;">99% Akurat</p>
                        </div>
                    </div>

                    <div class="floating-badge" style="bottom: 20%; right: -20px; animation: float 5s ease-in-out infinite 1s;">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold small">Best Seller</p>
                            <p class="mb-0 text-muted" style="font-size: 10px;">Kualitas Top</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section class="mb-4 py-3">
        <div class="text-center mb-4">
            <h2 class="fw-bold h2 mb-2 text-dark">Layanan Unggulan Kami</h2>
            <p class="text-muted small">Kami menyediakan berbagai macam jasa cetak dengan kualitas premium.</p>
        </div>
        
        <div class="row g-4">
            @php
                $services = [
                    ['icon' => 'fa-image', 'title' => 'Cetak Banner', 'desc' => 'Banner outdoor & indoor dengan bahan tebal dan warna tajam.'],
                    ['icon' => 'fa-print', 'title' => 'Cetak Sticker', 'desc' => 'Sticker label, cutting, dan vinyl untuk branding produk.'],
                    ['icon' => 'fa-envelope', 'title' => 'Cetak Undangan', 'desc' => 'Desain undangan elegan untuk momen spesial Anda.'],
                    ['icon' => 'fa-plus', 'title' => 'Custom Printing', 'desc' => 'Cetak kartu nama, brosur, poster, dan lainnya.']
                ];
            @endphp
            @foreach($services as $service)
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas {{ $service['icon'] }}"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-dark">{{ $service['title'] }}</h4>
                    <p class="text-muted small mb-0">{{ $service['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- FEATURED PRODUCTS -->
    <section class="py-3">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold h2 mb-1 text-dark">Produk Terbaru</h2>
                <p class="text-muted small mb-0">Koleksi produk cetak terbaik kami.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-link text-primary fw-bold text-decoration-none">
                Lihat Katalog <i class="fas fa-arrow-right ms-2 small"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="product-card-premium">
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                        <div class="product-img-container mb-4">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://picsum.photos/seed/ba-'.$product->id.'/400/400' }}" 
                                 alt="{{ $product->name }}">
                            <div class="position-absolute top-0 start-0 p-3">
                                <span class="badge bg-white text-primary rounded-pill px-3 py-2 shadow-sm fw-bold">{{ $product->category->name }}</span>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <h5 class="fw-bold text-dark mb-3 text-truncate" style="max-width: 90%;">{{ $product->name }}</h5>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <p class="mb-0 text-muted small fw-bold text-uppercase" style="font-size: 8px;">Mulai Dari</p>
                                    <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                                </div>
                                <span class="btn btn-primary rounded-pill px-4 btn-sm fw-bold text-white">Detail & Pesan</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endsection