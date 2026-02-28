@extends('layouts.app')

@section('content')
<head>
    <script type="text/javascript"
		src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-mqmvrVZuR8bqb4AN">
    </script>
</head>
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
                <div class="col-md-5 mb-4">
                   @foreach($order->details as $detail)
        <tr>
            <td>
                <strong>{{ $detail->product->name }}</strong>
                <img src="{{ $detail->image_detail ? asset('storage/'.$detail->image_detail) : 'https://via.placeholder.com/500x500?text=No+Image' }}" 
                    class="img-fluid rounded shadow-sm w-100">
                @if($detail->specs_snapshot)
                    <br><small class="text-muted">Specs: {{ $detail->specs_snapshot }}</small>
                @endif
            </td>
            
            <td>{{ $detail->quantity }}</td>
            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    </div>
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

                @if($order->status == 'unpaid')
                    <button id="pay-button" class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                    </button>
                @endif

                {{-- checkout midtrans --}}
                <div id="snap-container"></div>
<form id="payment-success-form" action="{{ route('orders.payment_success', $order->id) }}" method="POST" style="display: none;">
    @csrf
</form>
                     <script type="text/javascript">
                        // For example trigger on button clicked, or any time you need
                        var payButton = document.getElementById('pay-button');
                        payButton.addEventListener('click', function () {
                            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                            window.snap.pay('{{ $order->snap_token }}', {onSuccess: function(result){
                                // Auto-submit the hidden form to update database and redirect
                                document.getElementById('payment-success-form').submit();
                                },
                                onPending: function(result){
                                    alert("Menunggu pembayaran Anda!");
                                },
                                onError: function(result){
                                    alert("Pembayaran gagal!");
                                },
                                onClose: function(){
                                    // Customer closed the popup without finishing
                                }});
                            // customer will be redirected after completing payment pop-up
                        });
                    </script>
            </div>
        </div>
    </div>
</div>
@endsection