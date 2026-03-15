@extends('layouts.app')

@section('content')

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-mqmvrVZuR8bqb4AN"></script>

<div class="container py-5">
<div style="max-width:1100px;margin:0 auto">

    <div class="bg-white rounded-lg shadow-sm overflow-hidden" style="border:1px solid rgba(34,53,96,0.1)">

        {{-- Header --}}
        <div style="background:#223560;padding:20px 28px;display:flex;align-items:center;justify-content:space-between">
            <div>
                <p style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.5);margin:0 0 3px">Invoice</p>
                <h5 style="font-size:16px;font-weight:700;color:white;margin:0">#{{ $order->invoice_number }}</h5>
            </div>
            <div>
                @if($order->status == 'unpaid')
                    <span style="font-size:11px;font-weight:600;padding:5px 14px;border-radius:6px;background:rgba(251,191,36,0.2);color:#fbbf24;border:1px solid rgba(251,191,36,0.3)">Belum Dibayar</span>
                @elseif($order->status == 'paid')
                    <span style="font-size:11px;font-weight:600;padding:5px 14px;border-radius:6px;background:rgba(16,185,129,0.2);color:#34d399;border:1px solid rgba(16,185,129,0.3)">Lunas</span>
                @elseif($order->status == 'processing')
                    <span style="font-size:11px;font-weight:600;padding:5px 14px;border-radius:6px;background:rgba(59,130,246,0.2);color:#93c5fd;border:1px solid rgba(59,130,246,0.3)">Diproses</span>
                @elseif($order->status == 'completed')
                    <span style="font-size:11px;font-weight:600;padding:5px 14px;border-radius:6px;background:rgba(16,185,129,0.2);color:#34d399;border:1px solid rgba(16,185,129,0.3)">Selesai</span>
                @elseif($order->status == 'cancelled')
                    <span style="font-size:11px;font-weight:600;padding:5px 14px;border-radius:6px;background:rgba(239,68,68,0.2);color:#f87171;border:1px solid rgba(239,68,68,0.3)">Dibatalkan</span>
                @endif
            </div>
        </div>

        <div style="padding:24px 28px">

            {{-- Info Pelanggan & Tanggal --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid rgba(34,53,96,0.08)">
                <div>
                    <p style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:rgba(34,53,96,0.4);margin:0 0 6px">Ditagihkan Kepada</p>
                    <p style="font-weight:700;color:#223560;font-size:14px;margin:0">{{ $order->user->name }}</p>
                    <p style="font-size:12px;color:rgba(34,53,96,0.5);margin:3px 0 0">{{ $order->user->email }}</p>
                    @if($order->user->phone)
                    <p style="font-size:12px;color:rgba(34,53,96,0.5);margin:2px 0 0">{{ $order->user->phone }}</p>
                    @endif
                </div>
                <div style="text-align:right">
                    <p style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:rgba(34,53,96,0.4);margin:0 0 6px">Tanggal Order</p>
                    <p style="font-weight:700;color:#223560;font-size:14px;margin:0">{{ $order->created_at->format('d F Y') }}</p>
                    <p style="font-size:12px;color:rgba(34,53,96,0.5);margin:3px 0 0">{{ $order->created_at->format('H:i') }} WIB</p>
                </div>
            </div>

            {{-- Tabel Produk --}}
            <div style="overflow-x:auto;margin-bottom:24px">
                <table style="width:100%;border-collapse:collapse;font-size:13px">
                    <thead>
                        <tr style="background:rgba(34,53,96,0.04)">
                            <th style="text-align:left;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Deskripsi Produk</th>
                            <th style="text-align:center;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Qty</th>
                            <th style="text-align:right;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Harga</th>
                            <th style="text-align:right;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $detail)
                        <tr style="border-top:1px solid rgba(34,53,96,0.07)">
                            <td style="padding:14px 16px;vertical-align:top">
                                <p style="font-weight:700;color:#223560;font-size:13px;margin:0 0 4px">{{ $detail->product->name }}</p>

                                {{-- Specs --}}
                                @if($detail->specs_detail)
                                <p style="font-size:11px;color:rgba(34,53,96,0.5);margin:0 0 4px">
                                    @foreach($detail->specs_detail as $key => $val)
                                        {{ $key }}: {{ $val }}@if(!$loop->last) · @endif
                                    @endforeach
                                </p>
                                @endif

                                {{-- Note --}}
                                @if($detail->note_detail)
                                <p style="font-size:11px;color:#f59e0b;margin:0 0 6px">
                                    <i class="fas fa-sticky-note" style="font-size:9px"></i> {{ $detail->note_detail }}
                                </p>
                                @endif

                                {{-- File Desain --}}
                                @if($detail->image_detail)
                                <div style="margin-top:8px">
                                    <img src="{{ asset('storage/'.$detail->image_detail) }}"
                                         onclick="openPreview('{{ asset('storage/'.$detail->image_detail) }}')"
                                         style="width:80px;height:80px;object-fit:cover;border-radius:6px;cursor:pointer;display:block;margin-bottom:6px;border:1px solid rgba(34,53,96,0.1)">
                                    <div style="display:flex;gap:6px">
                                        <button type="button" onclick="openPreview('{{ asset('storage/'.$detail->image_detail) }}')"
                                                style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:600;padding:4px 10px;border-radius:5px;background:rgba(59,130,246,0.1);color:#3B82F6;border:none;cursor:pointer">
                                            <i class="fas fa-eye" style="font-size:9px"></i> Preview
                                        </button>
                                        <a href="{{ asset('storage/'.$detail->image_detail) }}" download
                                           style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:600;padding:4px 10px;border-radius:5px;background:#3B82F6;color:#fff;text-decoration:none">
                                            <i class="fas fa-download" style="font-size:9px"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td style="padding:14px 16px;text-align:center;font-weight:600;color:#223560;vertical-align:top">{{ $detail->quantity }}</td>
                            <td style="padding:14px 16px;text-align:right;color:rgba(34,53,96,0.6);vertical-align:top">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td style="padding:14px 16px;text-align:right;font-weight:600;color:#223560;vertical-align:top">Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="border-top:2px solid rgba(34,53,96,0.1)">
                            <td colspan="3" style="padding:14px 16px;text-align:right;font-weight:700;color:#223560;font-size:14px">Total Tagihan</td>
                            <td style="padding:14px 16px;text-align:right;font-weight:700;color:#3B82F6;font-size:15px">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Action --}}
            @if($order->status == 'unpaid' && auth()->user()?->role == 'customer')
            <div style="display:flex;justify-content:flex-end">
                <button id="pay-button"
                        style="display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:600;padding:10px 24px;border-radius:8px;background:#3B82F6;color:#fff;border:none;cursor:pointer"
                        onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
                    <i class="fas fa-credit-card" style="font-size:13px"></i> Bayar Sekarang
                </button>
            </div>
            @endif

        </div>
    </div>

</div>
</div>

{{-- Modal Preview Gambar --}}
<div id="modalPreview" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.8);align-items:center;justify-content:center"
     onclick="if(event.target===this)this.style.display='none'">
    <div style="position:relative;max-width:680px;width:100%;margin:0 16px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <a id="previewDownload" href="#" download
               style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;padding:7px 16px;border-radius:6px;background:#3B82F6;color:#fff;text-decoration:none">
                <i class="fas fa-download" style="font-size:10px"></i> Unduh File
            </a>
            <button type="button" onclick="document.getElementById('modalPreview').style.display='none'"
                    style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;padding:7px 16px;border-radius:6px;background:rgba(255,255,255,0.15);color:#fff;border:none;cursor:pointer"
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                <i class="fas fa-times" style="font-size:10px"></i> Tutup
            </button>
        </div>
        <img id="previewImg" src="" style="width:100%;max-height:78vh;object-fit:contain;border-radius:8px;display:block">
    </div>
</div>

{{-- Form payment success (hidden) --}}
<form id="payment-success-form" action="{{ route('orders.payment_success', $order->id) }}" method="POST" style="display:none">
    @csrf
</form>

<script>
    function openPreview(src) {
        document.getElementById('previewImg').src = src;
        document.getElementById('previewDownload').href = src;
        var modal = document.getElementById('modalPreview');
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
    }

    @if($order->status == 'unpaid')
    var payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function() {
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) {
                    document.getElementById('payment-success-form').submit();
                },
                onPending: function(result) {
                    alert('Menunggu pembayaran Anda!');
                },
                onError: function(result) {
                    alert('Pembayaran gagal!');
                },
                onClose: function() {}
            });
        });
    }
    @endif
</script>

@endsection