@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Katalog Produk</h2>
    
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Filter Kategori
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('products.index') }}">Semua Kategori</a></li>
            @foreach($categories as $cat)
                <li><a class="dropdown-item" href="{{ route('products.index', ['category' => $cat->name]) }}">{{ $cat->name }}</a></li>
            @endforeach
        </ul>
    </div>
</div>

<div class="row g-4">
    @foreach($products as $product)
    <div class="col-md-3 col-6">
        <div class="card h-100 card-product">
            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}" 
                 class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            
            <div class="card-body d-flex flex-column">
                <span class="badge bg-secondary w-auto align-self-start mb-2">{{ $product->category->name }}</span>
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="text-primary fw-bold mb-0">Rp {{ number_format($product->price, 0, ',', '.') }} <small class="text-muted">/ pcs</small></p>
                
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary mt-auto w-100">
                    Detail & Pesan
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection