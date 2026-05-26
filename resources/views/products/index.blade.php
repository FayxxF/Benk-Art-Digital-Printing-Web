@extends('layouts.app')

@section('content')
<style>
    /* Sidebar Style */
    .sidebar-category { background: #fff; border-radius: 1.5rem; padding: 1.5rem; border: 1px solid #f1f3f5; }
    .cat-btn { display: block; width: 100%; text-align: left; padding: 0.75rem 1rem; border-radius: 0.75rem; color: #64748b; font-weight: 500; transition: all 0.2s; text-decoration: none; margin-bottom: 0.25rem; }
    .cat-btn:hover { background: #f8fafc; color: #223560; }
    .cat-btn.active { background: #223560; color: #fff; }

    /* Product Card Style */
    .product-grid-card { display: flex; flex-direction: column; border-radius: 1.25rem; overflow: hidden; border: 1px solid #f1f3f5; transition: all 0.3s ease; background: white; }
    .product-grid-card:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.06); transform: translateY(-5px); }
    .product-img-wrapper { height: 200px; overflow: hidden; }
    .product-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .product-grid-card:hover img { transform: scale(1.05); }

    /* Discount Badge */
    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ef4444;
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 800;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    /* Pagination Style */
    .products-pagination-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
        justify-content: center;
        margin-top: 1.5rem;
    }
    .products-pagination-info {
        font-size: 0.75rem;
        color: #334155;
        opacity: 0.65;
    }
    .products-pagination-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        align-items: center;
        justify-content: center;
    }
    .products-pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        min-width: 2rem;
        height: 2rem;
        border-radius: 0.75rem;
        background: rgba(34, 53, 96, 0.06);
        color: #223560;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .products-pagination-btn:hover {
        background: rgba(34, 53, 96, 0.12);
    }
    .products-pagination-btn-current {
        background: #3b82f6;
        color: #ffffff;
    }
    .products-pagination-btn-disabled {
        background: rgba(34, 53, 96, 0.05);
        color: #334155;
        opacity: 0.25;
        cursor: not-allowed;
        pointer-events: none;
    }
    .products-pagination-btn i {
        font-size: 0.7rem;
    }
</style>

<div class="container py-5">
    <div class="row g-4">
        {{-- SIDEBAR KATEGORI --}}
        <div class="col-lg-3">
            <div class="sidebar-category sticky-top" style="top: 20px;">
                <h6 class="fw-bold mb-3 px-2">Kategori Produk</h6>
                <a href="{{ route('products.index') }}" class="cat-btn {{ !request('category') ? 'active' : '' }}">Semua Produk</a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" 
                       class="cat-btn {{ request('category') == $cat->id ? 'active' : '' }}">
                       {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            {{-- SEARCH BAR --}}
            <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                <div class="row g-2">
                    <div class="col-12 col-md-7">
                        <div class="input-group shadow-sm border rounded-pill p-1">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 rounded-pill px-3" placeholder="Cari produk...">
                            <button class="btn btn-primary rounded-pill px-3" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="d-flex gap-2 align-items-center w-100">
                            <select name="sort" class="form-select rounded-pill px-3 py-2 border" style="min-width:0;" onchange="this.form.submit()">
                                <option value="">Urutkan berdasarkan</option>
                                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>A - Z</option>
                                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Z - A</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            {{-- GRID PRODUK --}}
            <div id="productGrid" class="row g-4">
                @foreach($products as $product)
                <div class="col-md-4 col-lg-3 d-flex align-items-stretch">
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark w-100 d-flex flex-column">
                        <div class="product-grid-card h-100 position-relative flex-grow-1">
                            @if($product->hasActiveDiscount())
                                <div class="discount-badge">
                                    {{ $product->discount_percentage }}% OFF
                                </div>
                            @endif
                            <div class="product-img-wrapper">
                                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <small class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $product->category->name }}</small>
                                <div class="mt-0 mb-1">
                                    @if($product->stock > 10)
                                        <small class="text-muted" style="font-size: 0.65rem;">Stok: <span class="fw-bold">{{ $product->stock }}</span></small>
                                    @elseif($product->stock > 0)
                                        <small class="text-warning fw-bold" style="font-size: 0.65rem;">Stok Menipis: {{ $product->stock }}</small>
                                    @else
                                        <small class="text-danger fw-bold" style="font-size: 0.65rem;">Stok Habis</small>
                                    @endif
                                </div>
                                <h6 class="fw-bold my-1 text-truncate">{{ $product->name }}</h6>
                                <div class="mb-3 d-flex flex-column justify-content-end" style="min-height: 48px;">
                                    @if($product->hasActiveDiscount())
                                        <p class="text-muted text-decoration-line-through small mb-0" style="font-size: 0.75rem; line-height: 1.2;">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        <p class="fw-bold text-danger mb-0" style="font-size: 1.1rem; line-height: 1.2;">
                                            Rp {{ number_format($product->calculatePrice(), 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="fw-bold mb-0" style="font-size: 1.1rem; line-height: 1.2;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                                <span class="btn btn-outline-dark btn-sm w-100 rounded-pill mt-auto">Detail & Pesan</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            @if($products->hasPages())
            <div class="products-pagination-wrapper">
                <div class="products-pagination-info">
                    Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
                </div>
                <div class="products-pagination-controls">
                    @if($products->onFirstPage())
                        <span class="products-pagination-btn products-pagination-btn-disabled">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="products-pagination-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                        @if($page == $products->currentPage())
                            <span class="products-pagination-btn products-pagination-btn-current">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="products-pagination-btn">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="products-pagination-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="products-pagination-btn products-pagination-btn-disabled">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Script Infinite Scroll tetap
</script>
@endsection