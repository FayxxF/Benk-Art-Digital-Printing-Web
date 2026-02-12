@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="mb-0">INVOICE</h5>
                <small>#{{ $order->invoice_number }}</small>
            </div>
            <div>
                @if($order->status == 'unpaid')
                    <span class="badge bg-warning text-dark px-3 py-2">Belum Dibayar</span>
                @elseif($order->status == 'paid')
                    <span class="badge bg-success px-3 py-2">Lunas</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">{{ ucfirst($order->status) }}</span>
                @endif
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Ditagihkan Kepada:</h6>
                    <p class="fw-bold mb-0">{{ $order->user->name }}</p>
                    <p class="mb-0">{{ $order->user->email }}</p>
                    <p>{{ $order->user->phone }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="text-muted">Tanggal Order:</h6>
                    <p class="fw-bold">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $detail)
                        <tr>
                            <td>
                                <strong>{{ $detail->product->name }}</strong>
                                <br>
                                @if($detail->specs_snapshot)
                                    <small class="text-muted">
                                        @foreach($detail->specs_snapshot as $key => $val)
                                            {{ $key }}: {{ $val }} | 
                                        @endforeach
                                    </small>
                                @endif
                                
                                @if($detail->note_snapshot)
                                    <br><small class="text-warning fst-italic">Note: {{ $detail->note_snapshot }}</small>
                                @endif
                                
                                @if($detail->image_snapshot)
                                    <br><a href="{{ asset('storage/'.$detail->image_snapshot) }}" class="text-primary text-decoration-none text-sm"><i class="fas fa-link"></i> File Desain</a>
                                @endif
                            </td>
                            <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total Tagihan</td>
                            <td class="text-end fw-bold bg-light">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ $order->whatsapp_link }}" target="_blank" class="btn btn-success">
                    <i class="fab fa-whatsapp me-2"></i> Konfirmasi ke Admin
                </a>

                @if($order->status == 'unpaid')
                    <button id="pay-button" class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection