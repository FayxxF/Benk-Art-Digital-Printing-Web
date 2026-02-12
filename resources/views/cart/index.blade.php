@extends('layouts.app')

@section('content')
<h3 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h3>

@if($cartItems->isEmpty())
    <div class="text-center py-5">
        <div class="mb-3"><i class="fas fa-shopping-basket fa-3x text-muted"></i></div>
        <h5>Keranjang Anda kosong</h5>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Mulai Belanja</a>
    </div>
@else
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Detail Order</th>
                                <th>Harga Satuan</th>
                                <th>Qty</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td width="25%">
                                    <strong>{{ $item->product->name }}</strong>
                                    @if($item->image_request)
                                        <br>
                                        <a href="{{ asset('storage/' . $item->image_request) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fas fa-file-image me-1"></i> Lihat File
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->specs_request)
                                        <ul class="list-unstyled mb-1 text-sm text-muted" style="font-size: 0.85rem;">
                                            @foreach($item->specs_request as $key => $val)
                                                <li>• {{ $key }}: <b>{{ $val }}</b></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if($item->description_request)
                                        <small class="text-warning">"{{ Str::limit($item->description_request, 30) }}"</small>
                                    @endif
                                </td>
                                <td>
                                    Rp {{ number_format($item->product->calculatePrice($item->specs_request), 0, ',', '.') }}
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus item ini?')">
                                            <i class="fas fa-trash"></i>
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

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Ringkasan Belanja</div>
                <div class="card-body">
                    @php
                        $grandTotal = 0;
                        foreach($cartItems as $c) {
                            $grandTotal += $c->product->calculatePrice($c->specs_request) * $c->quantity;
                        }
                    @endphp
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Harga</span>
                        <span class="fw-bold fs-5">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            Checkout Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection