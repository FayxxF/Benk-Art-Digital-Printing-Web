@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-5 mb-4">
        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/500x500?text=No+Image' }}" 
             class="img-fluid rounded shadow-sm w-100">
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">
                <span class="badge bg-info mb-2">{{ $product->category->name }}</span>
                <h2 class="fw-bold">{{ $product->name }}</h2>
                <h3 class="text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                <p class="text-muted">{{ $product->description }}</p>
                <hr>

                <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($product->specs)
                        <h5 class="mb-3">Pilih Spesifikasi:</h5>
                        <div class="row">
                            @foreach($product->specs as $spec)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ $spec['name'] }}</label>
                                    <select name="specs[{{ $spec['name'] }}]" class="form-select" required>
                                        <option value="">-- Pilih {{ $spec['name'] }} --</option>
                                        @foreach($spec['options'] as $option)
                                            <option value="{{ $option['value'] }}">
                                                {{ $option['value'] }}
                                                @if($option['price'] > 0)
                                                    (+ Rp {{ number_format($option['price'], 0, ',', '.') }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Desain (Opsional)</label>
                        <input type="file" name="image_request" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Format: JPG, PNG, PDF. Max 5MB.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Tambahan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Tolong warna dibuat lebih terang..."></textarea>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Jumlah (Qty)</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-cart-plus me-2"></i> Masuk Keranjang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection