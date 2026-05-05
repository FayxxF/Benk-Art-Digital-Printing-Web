@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="fw-black mb-4"><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h3>

    @if($cartItems->isEmpty())
        <div class="text-center py-5 bg-white rounded-5 shadow-sm border">
            <div class="mb-3 text-primary"><i class="fas fa-shopping-basket fa-4x"></i></div>
            <h5 class="fw-bold">Keranjang Anda masih kosong</h5>
            <p class="text-muted small">Temukan produk impian Anda sekarang.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Belanja</a>
        </div>
    @else
        <div class="row g-4">
            {{-- Bagian Kiri: Tabel Produk --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead>
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-0">Produk</th>
                                        <th>Detail Order</th>
                                        <th>Upload File</th> {{-- Kolom Khusus File --}}
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    <tr class="border-bottom">
                                        {{-- 1. Produk --}}
                                        <td class="ps-0 py-4">
                                            <div class="fw-bold text-dark">{{ $item->product->name }}</div>
                                        </td>
                                        
                                        {{-- 2. Detail Order --}}
                                        <td>
                                            @if($item->specs_request)
                                                <ul class="list-unstyled mb-1 small text-muted">
                                                    @foreach($item->specs_request as $key => $val)
                                                        <li>• {{ $key }}: <b>{{ $val }}</b></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            @if($item->description_request)
                                                <small class="text-warning">"{{ Str::limit($item->description_request, 30) }}"</small>
                                            @endif
                                        </td>

                                        {{-- 3. Upload File (Sudah dipindah ke sini) --}}
                                        <td>
                                            @if($item->image_request)
                                                <a href="{{ asset('storage/' . $item->image_request) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none">
                                                    <i class="fas fa-file-image me-1"></i> Lihat File
                                                </a>
                                            @else
                                                <span class="text-muted small">No File</span>
                                            @endif
                                        </td>

                                        {{-- 4. Harga --}}
                                        <td class="fw-bold text-primary">
                                            Rp {{ number_format($item->product->calculatePrice($item->specs_request), 0, ',', '.') }}
                                        </td>
                                        
                                        {{-- 5. Qty --}}
                                        <td>
                                            <span class="badge bg-light text-dark px-3">{{ $item->quantity }}</span>
                                        </td>
                                        
                                        {{-- 6. Aksi --}}
                                        <td class="text-end">
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger" onclick="return confirm('Hapus item ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan: Summary (Tetap) --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-4">Ringkasan Belanja</h6>
                        @php
                            $grandTotal = 0;
                            foreach($cartItems as $c) {
                                $grandTotal += $c->product->calculatePrice($c->specs_request) * $c->quantity;
                            }
                        @endphp
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Total Harga</span>
                            <span class="fw-black fs-4 text-dark">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow">
                                Checkout Sekarang <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection