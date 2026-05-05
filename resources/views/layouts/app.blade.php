<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benk Art Printing</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root { --primary-color: #0d6efd; --dark-color: #0B1A33; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #2D3748; min-height: 100vh; display: flex; flex-direction: column; }
        .fw-black { font-weight: 800; }
        
        /* Navbar */
        .navbar { background: white !important; border-bottom: 1px solid #f1f3f5; padding: 1.25rem 0; }
        .nav-link { color: #4A5568 !important; font-weight: 600; font-size: 0.95rem; transition: all 0.2s ease; padding: 0.5rem 1rem !important; }
        .nav-link:hover, .nav-link.active { color: var(--primary-color) !important; }
        .navbar-brand { font-size: 1.5rem; letter-spacing: -0.02em; color: var(--primary-color) !important; }
        
        /* Dropdown */
        .dropdown-menu { border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-radius: 1rem; padding: 0.75rem; }
        .dropdown-item { border-radius: 0.5rem; padding: 0.6rem 1rem; font-weight: 600; font-size: 0.85rem; color: #4A5568; }
        .dropdown-item:hover { background-color: #f8fafc; color: var(--primary-color); }
        
        /* Footer */
        .footer { background: var(--dark-color); color: white; padding: 5rem 0 2rem; margin-top: auto; }
        .footer h6 { font-weight: 700; color: white; margin-bottom: 1.5rem; }
        .footer-link { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.2s ease; font-size: 0.9rem; }
        .footer-link:hover { color: var(--primary-color); }
        .social-icon { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); border-radius: 50%; color: rgba(255,255,255,0.5); transition: 0.2s; }
        .social-icon:hover { background: var(--primary-color); color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-black" href="{{ route('home') }}">BENK ART</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Katalog</a></li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <a href="{{ route('cart.index') }}" class="btn btn-link text-dark position-relative p-2">
                            <i class="bi bi-cart fs-5"></i>
                            @if(auth()->user()->carts->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.35em 0.6em;">{{ auth()->user()->carts->count() }}</span>
                            @endif
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-link text-dark p-2" type="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle fs-5"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2 border-bottom mb-2"><p class="mb-0 fw-bold">{{ auth()->user()->name }}</p></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-receipt me-2"></i>Pesanan Saya</a></li>
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-graph-up me-2"></i>Dashboard</a></li>
                                @endif
                                <li><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="dropdown-item text-danger"><i class="bi bi-power me-2"></i>Keluar</button></form></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 px-3 py-2 fw-bold d-flex align-items-center">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1" style="margin-top:100px;">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show mb-4">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row g-5 mb-5 pb-5 border-bottom border-white border-opacity-10">
                <div class="col-lg-4">
                    <h3 class="fw-black mb-4">BENK ART</h3>
                    <p class="text-white text-opacity-50 small mb-4">Solusi percetakan digital terpercaya sejak 2015. Kami menghadirkan kualitas cetak terbaik dengan teknologi terkini untuk mendukung bisnis Anda.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h6>Tautan Cepat</h6>
                    <ul class="list-unstyled d-grid gap-2">
                        <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                        <li><a href="{{ route('products.index') }}" class="footer-link">Katalog Produk</a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h6>Layanan</h6>
                    <ul class="list-unstyled d-grid gap-2">
                        <li><a href="#" class="footer-link">Cetak Banner</a></li>
                        <li><a href="#" class="footer-link">Sticker Label</a></li>
                        <li><a href="#" class="footer-link">Kartu Nama</a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-lg-4">
                    <h6>Hubungi Kami</h6>
                    <ul class="list-unstyled d-grid gap-3 small text-white text-opacity-50">
                        <li class="d-flex gap-3"><i class="fas fa-map-marker-alt text-primary mt-1"></i> <span>Jl. Percetakan Raya No. 123, Jakarta Selatan</span></li>
                        <li class="d-flex gap-3"><i class="fas fa-phone-alt text-primary mt-1"></i> <span>+62 812 3456 7890</span></li>
                        <li class="d-flex gap-3"><i class="fas fa-envelope text-primary mt-1"></i> <span>info@benkart.com</span></li>
                    </ul>
                </div>
            </div>
            <div class="text-center text-white text-opacity-25 small">&copy; {{ date('Y') }} Benk Art Digital Printing. Seluruh Hak Cipta Dilindungi.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>