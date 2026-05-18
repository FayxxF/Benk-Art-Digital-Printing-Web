@extends('layouts.app')

@section('content')
<style>
    .product-img-detail { width: 100%; height: 450px; object-fit: cover; border-radius: 2rem; }
    .spec-select { cursor: pointer; transition: all 0.2s; }
    .price-tag { font-size: 2.5rem; font-weight: 900; color: #0d6efd; }
</style>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="bg-light rounded-5 overflow-hidden shadow-sm">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/500' }}" 
                     alt="{{ $product->name }}" class="product-img-detail">
            </div>
        </div>

        <div class="col-lg-6">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-3">{{ $product->category->name }}</span>
            <h1 class="fw-black mb-3">{{ $product->name }}</h1>
            <div class="mb-4 d-flex align-items-center flex-wrap gap-2">
                @if($product->discount_percentage != 0)
                    <span class="text-decoration-line-through text-muted fs-4 me-1">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="price-tag text-danger" id="displayPrice">Rp {{ number_format($product->calculatePrice(), 0, ',', '.') }}</span>
                    <span class="badge bg-danger text-white rounded-pill px-3 py-2 fw-bold align-middle shadow-sm">{{ $product->discount_percentage }}% OFF</span>
                @else
                    <span class="price-tag" id="displayPrice">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            
            <p class="text-muted mb-4">{{ $product->description }}</p>
            
            <div class="mb-4 p-3 rounded-4 bg-light d-inline-block border">
                @if($product->stock > 10)
                    <span class="text-muted small"><i class="fas fa-boxes me-2"></i>Stok Tersedia: <span class="fw-bold text-dark">{{ $product->stock }}</span></span>
                @elseif($product->stock > 0)
                    <span class="text-warning small fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Stok Menipis: {{ $product->stock }}</span>
                @else
                    <span class="text-danger small fw-bold"><i class="fas fa-times-circle me-2"></i>Stok Habis</span>
                @endif
            </div>

            <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" id="basePrice" value="{{ $product->calculatePrice() }}">

                {{-- Spesifikasi (Backend Anda) --}}
                @if($product->specs && count($product->specs) > 0)
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Pilih Spesifikasi</h5>
                    <div class="row g-3">
                        @foreach($product->specs as $spec)
                        <div class="col-md-6">
                            <label class="fw-bold small mb-2">{{ $spec['name'] }}</label>
                            <select name="specs[{{ $spec['name'] }}]" class="form-select spec-select rounded-3 py-2" onchange="updatePrice()" required>
                                <option value="" data-price="0">-- Pilih {{ $spec['name'] }} --</option>
                                @foreach($spec['options'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}">
                                    {{ $option['value'] }} {{ $option['price'] > 0 ? '(+Rp '.number_format($option['price'], 0, ',', '.').')' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Upload & Notes --}}
                <div class="mb-3">
                    <label class="fw-bold small mb-2">Upload Desain (Wajib)</label>
                    <input type="file" name="image_request" class="form-control rounded-3 py-2" accept="image/*,.pdf" required>
                </div>

                <div class="mb-4">
                    <label class="fw-bold small mb-2">Catatan Tambahan</label>
                    <textarea name="notes" class="form-control rounded-3 p-3" rows="2" placeholder="Jelaskan kebutuhan desain Anda..."></textarea>
                </div>

                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="number" name="quantity" value="1" min="1" class="form-control py-3 rounded-4 text-center fw-bold" required>
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-lg">
                            <i class="fas fa-cart-plus me-2"></i> Masuk Keranjang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Rekomendasi (Backend Anda) --}}
    @if($recommendedProducts->count() > 0)
    <div class="mt-5 pt-5 border-top">
        <h4 class="fw-bold mb-4">Sering Dibeli Bersama</h4>
        <div class="row g-4">
            @foreach($recommendedProducts as $rec)
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-2 position-relative d-flex flex-column">
                    @if($rec->discount_percentage != 0)
                        <span class="badge bg-danger text-white rounded-pill px-2 py-1 position-absolute top-0 end-0 m-2 shadow-sm" style="font-size: 0.6rem; z-index: 10;">{{ $rec->discount_percentage }}% OFF</span>
                    @endif
                    <img src="{{ $rec->image ? asset('storage/'.$rec->image) : 'https://via.placeholder.com/300' }}" class="rounded-3 mb-2" style="height: 150px; object-fit:cover;">
                    <div class="p-2 d-flex flex-column flex-grow-1">
                        <small class="text-primary fw-bold">{{ round($rec->rec_confidence * 100) }}% Match</small>
                        <h6 class="fw-bold text-truncate mb-1">{{ $rec->name }}</h6>
                        <div class="mb-2 d-flex flex-column justify-content-end" style="min-height: 38px;">
                            @if($rec->discount_percentage != 0)
                                <small class="text-decoration-line-through text-muted" style="font-size: 0.7rem; display: block; line-height: 1;">Rp {{ number_format($rec->price, 0, ',', '.') }}</small>
                                <span class="fw-black text-danger" style="font-size: 0.95rem; line-height: 1.2;">Rp {{ number_format($rec->calculatePrice(), 0, ',', '.') }}</span>
                            @else
                                <span class="fw-black text-primary" style="font-size: 0.95rem; line-height: 1.2;">Rp {{ number_format($rec->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $rec->id) }}" class="btn btn-sm btn-outline-dark w-100 rounded-pill mt-auto">Lihat</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    var basePrice = parseInt(document.getElementById('basePrice').value) || 0;

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updatePrice() {
        var extra = 0;
        document.querySelectorAll('.spec-select').forEach(function(sel) {
            var opt = sel.options[sel.selectedIndex];
            if (opt && opt.getAttribute('data-price')) {
                extra += parseInt(opt.getAttribute('data-price'));
            }
        });
        document.getElementById('displayPrice').textContent = formatRupiah(basePrice + extra);
    }
</script>
@endsection