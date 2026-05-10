@extends('layouts.app')

@section('content')

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-mqmvrVZuR8bqb4AN"></script>

<style>
    /* Benkart Premium Marketplace Style */
    .filter-section {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        position: relative;
        z-index: 100; /* Ensure it stays above content but below navbar if needed */
    }
    
    .search-pill-tkp {
        background: white;
        border-radius: 2.5rem;
        border: 1px solid #f1f5f9;
        padding: 0.4rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: 0.3s;
        display: flex;
        gap: 0.5rem;
    }
    .search-pill-tkp:focus-within {
        border-color: #0d6efd;
        box-shadow: 0 10px 25px rgba(13, 110, 253, 0.1);
    }
    
    .search-input-tkp {
        border-radius: 0.75rem;
        padding: 0.6rem 1rem;
        border: 1px solid #e5e7eb;
        background: #fcfdfe;
        font-size: 0.9rem;
    }
    .search-input-tkp:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        outline: none;
    }
    
    .status-tab-container {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        margin-top: 1.25rem;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .status-tab-container::-webkit-scrollbar { display: none; } /* Chrome/Safari */
    
    .status-tab {
        padding: 0.4rem 1.1rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        color: #64748b;
        font-weight: 600;
        font-size: 0.8rem;
        white-space: nowrap;
        text-decoration: none;
        transition: 0.2s;
        background: white;
    }
    
    .status-tab:hover {
        background: #f8fafc;
        color: #0d6efd;
        border-color: #cbd5e1;
    }
    
    .status-tab.active {
        background: #f0f7ff;
        color: #0d6efd;
        border-color: #0d6efd;
    }

    .order-card-tkp {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.25rem;
        padding: 1.25rem;
        transition: 0.2s;
    }
    
    .order-card-tkp:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .status-badge-tkp {
        padding: 0.2rem 0.6rem;
        border-radius: 0.4rem;
        font-weight: 800;
        font-size: 0.65rem;
        text-transform: capitalize;
    }
    
    .status-unpaid { background: #fffbeb; color: #92400e; }
    .status-paid { background: #ecfdf5; color: #059669; }
    .status-processing { background: #eff6ff; color: #1e40af; }
    .status-completed { background: #f0fdf4; color: #166534; }
    .status-cancelled { background: #fef2f2; color: #991b1b; }

    .product-img-tkp {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid #f1f5f9;
    }

    .btn-action-tkp {
        padding: 0.5rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 800;
        font-size: 0.85rem;
        transition: 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-tkp-primary {
        background: #0d6efd;
        color: white;
        border: none;
    }
    
    .btn-tkp-primary:hover {
        background: #0b5ed7;
        color: white;
    }
    
    .btn-tkp-outline {
        border: 1px solid #0d6efd;
        color: #0d6efd;
        background: white;
    }
    
    .btn-tkp-outline:hover {
        background: #f0f7ff;
    }

    .text-brand { color: #0d6efd !important; }

    .pagination-btn-tkp {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        font-weight: 800;
        font-size: 0.85rem;
        transition: 0.2s;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        background: white;
        color: #64748b;
    }
    .pagination-btn-tkp:hover {
        background: #f8fafc;
        color: #0d6efd;
        border-color: #cbd5e1;
    }
    .pagination-btn-tkp.active {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .pagination-btn-tkp.disabled {
        opacity: 0.25;
        pointer-events: none;
        background: #f8fafc;
    }
</style>

<div class="container py-4">
    {{-- Search & Filter Section --}}
    <div class="filter-section">
        <form method="GET" action="{{ route('orders.index') }}" id="filterForm">
            {{-- Keep status if set --}}
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="search-pill-tkp">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control border-0 bg-transparent px-4 py-2 shadow-none" 
                               placeholder="Cari nomor invoice Anda..."
                               style="font-size: 0.9rem;">
                        <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit">Cari</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select search-input-tkp" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="form-control search-input-tkp ps-3" 
                           onchange="this.form.submit()">
                </div>
            </div>

            <div class="status-tab-container">
                <span class="fw-bold text-dark me-2 mt-1 small">Status</span>
                <a href="{{ route('orders.index', request()->except('status')) }}" class="status-tab {{ !request('status') ? 'active' : '' }}">Semua</a>
                @foreach(['unpaid'=>'Menunggu Pembayaran', 'paid'=>'Dibayar', 'processing'=>'Proses Cetak', 'completed'=>'Selesai', 'cancelled'=>'Dibatalkan'] as $value => $label)
                    <a href="{{ route('orders.index', array_merge(request()->all(), ['status' => $value])) }}" 
                       class="status-tab {{ request('status') == $value ? 'active' : '' }}">
                       {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('orders.index') }}" class="text-brand fw-bold small ms-auto mt-1 text-decoration-none">Reset Filter</a>
            </div>
        </form>
    </div>

    {{-- Orders Loop --}}
    @forelse($orders as $order)
    <div class="order-card-tkp">
        {{-- Header Card --}}
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="text-brand opacity-75">
                <i class="fas fa-shopping-bag small"></i>
            </div>
            <span class="fw-bold text-dark small">Belanja</span>
            <span class="text-muted small ms-1">{{ $order->created_at->format('d M Y') }}</span>
            <span class="status-badge-tkp status-{{ $order->status }} ms-2">
                {{ $order->status == 'unpaid' ? 'Menunggu Pembayaran' : 
                  ($order->status == 'paid' ? 'Dibayar' : 
                  ($order->status == 'processing' ? 'Proses Cetak' : 
                  ($order->status == 'completed' ? 'Selesai' : 'Dibatalkan'))) }}
            </span>
            <span class="text-muted small ms-2 opacity-50" style="font-family: monospace;">{{ $order->invoice_number }}</span>
        </div>

        {{-- Body Card --}}
        <div class="row align-items-center g-3">
            <div class="col-auto">
                @php $firstItem = $order->details->first(); @endphp
                <img src="{{ $firstItem && $firstItem->product->image ? asset('storage/'.$firstItem->product->image) : 'https://picsum.photos/seed/ba-'.$order->id.'/200/200' }}" 
                     class="product-img-tkp" alt="Product">
            </div>
            <div class="col">
                <h6 class="fw-bold text-dark mb-1">{{ $firstItem->product->name ?? 'Produk Custom' }}</h6>
                <p class="text-muted small mb-0">
                    {{ $order->details->count() }} barang x Rp {{ number_format($firstItem->price ?? 0, 0, ',', '.') }}
                </p>
                @if($order->details->count() > 1)
                    <p class="text-muted mt-1 mb-0" style="font-size: 11px;">+{{ $order->details->count() - 1 }} produk lainnya</p>
                @endif
            </div>
            <div class="col-lg-3 text-lg-end border-start ps-lg-4">
                <p class="text-muted small mb-1 d-none d-lg-block">Total Belanja</p>
                <h6 class="fw-black text-dark mb-0 fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h6>
            </div>
        </div>

        {{-- Footer Card --}}
        <div class="d-flex justify-content-end align-items-center gap-3 mt-3 pt-3">
            <a href="{{ route('orders.show', $order->id) }}" class="text-brand fw-bold text-decoration-none small me-auto">Lihat Detail Transaksi</a>
            
            @if($order->status == 'unpaid')
                <a href="{{ route('orders.show', $order->id) }}" class="btn-action-tkp btn-tkp-primary">Bayar Sekarang</a>
            @else
                <a href="#" class="btn-action-tkp btn-tkp-outline d-none d-sm-inline-block">Tulis Ulasan</a>
                <a href="{{ route('products.index') }}" class="btn-action-tkp btn-tkp-primary">Beli Lagi</a>
            @endif
            {{-- REMOVED ELLIPSIS BUTTON AS REQUESTED --}}
        </div>
    </div>
    @empty
    <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
        <div class="mb-4 text-muted opacity-25" style="font-size: 5rem;"><i class="fas fa-receipt"></i></div>
        <h4 class="fw-bold text-dark mb-2">Transaksi tidak ditemukan</h4>
        <p class="text-muted mb-4">Coba sesuaikan filter atau cari nomor invoice lain.</p>
        <a href="{{ route('orders.index') }}" class="btn-action-tkp btn-tkp-outline px-5">Reset Semua Filter</a>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-5">
        <div class="d-flex justify-content-center align-items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($orders->onFirstPage())
                <span class="pagination-btn-tkp disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $orders->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="pagination-btn-tkp">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                @if ($page == $orders->currentPage())
                    <span class="pagination-btn-tkp active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}" class="pagination-btn-tkp">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="pagination-btn-tkp">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="pagination-btn-tkp disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
        
        <div class="text-center mt-3">
            <p class="text-muted" style="font-size: 0.75rem; font-weight: 600;">
                Menampilkan {{ $orders->firstItem() }} sampai {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
            </p>
        </div>
    </div>
    @endif
</div>
@endsection