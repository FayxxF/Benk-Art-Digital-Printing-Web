@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest text-navy mb-1" style="opacity:0.45">Manajemen</p>
        <h2 class="text-3xl font-bold text-navy">Pesanan</h2>
    </div>
    {{-- Tombol Export CSV --}}
    <a href="{{ route('admin.orders.index') }}?{{ http_build_query(array_merge(request()->all(), ['export' => 'csv'])) }}"
       class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-md"
       style="background:#223560;text-decoration:none"
       onmouseover="this.style.background='#1a2a4e'" onmouseout="this.style.background='#223560'">
        <i class="fas fa-file-csv text-xs"></i> Export CSV
    </a>
</div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.orders.index') }}">
<div class="bg-white rounded-lg shadow-sm p-4 mb-4 flex flex-col sm:flex-row gap-3 flex-wrap" style="border:1px solid rgba(34,53,96,0.1)">

    <div class="relative flex-1" style="min-width:180px">
        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-navy text-xs" style="opacity:0.35"></i>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari invoice / nama..."
               class="w-full pl-9 pr-4 py-2 rounded-md text-sm text-navy outline-none"
               style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
               onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'"
               onblur="this.style.borderColor='rgba(34,53,96,0.15)';this.style.background='#f8f9fb'">
    </div>

    <select name="status"
            class="px-3 py-2 rounded-md text-sm text-navy outline-none"
            style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb;min-width:140px"
            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
        <option value="">Semua Status</option>
        <option value="unpaid"     {{ request('status') == 'unpaid'     ? 'selected' : '' }}>Unpaid</option>
        <option value="paid"       {{ request('status') == 'paid'       ? 'selected' : '' }}>Paid</option>
        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
        <option value="completed"  {{ request('status') == 'completed'  ? 'selected' : '' }}>Completed</option>
        <option value="cancelled"  {{ request('status') == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
    </select>

    <input type="date" name="date_from" value="{{ request('date_from') }}"
           class="px-3 py-2 rounded-md text-sm text-navy outline-none"
           style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
           onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">

    <input type="date" name="date_to" value="{{ request('date_to') }}"
           class="px-3 py-2 rounded-md text-sm text-navy outline-none"
           style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
           onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">

    <button type="submit"
            class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-md"
            style="background:#3B82F6;border:none;cursor:pointer"
            onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
        <i class="fas fa-search text-xs"></i> Cari
    </button>

    @if(request('search') || request('status') || request('date_from') || request('date_to'))
    <a href="{{ route('admin.orders.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-md text-navy"
       style="background:rgba(34,53,96,0.08);text-decoration:none"
       onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
        <i class="fas fa-times text-xs"></i> Reset
    </a>
    @endif

</div>
</form>

{{-- Table --}}
<div class="bg-white rounded-lg shadow-sm overflow-hidden" style="border:1px solid rgba(34,53,96,0.1)">

    <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid rgba(34,53,96,0.06)">
        <p class="text-xs text-navy" style="opacity:0.45">
            Menampilkan <span class="font-semibold">{{ $orders->firstItem() ?? 0 }}</span>–<span class="font-semibold">{{ $orders->lastItem() ?? 0 }}</span>
            dari <span class="font-semibold">{{ $orders->total() }}</span> pesanan
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:rgba(34,53,96,0.04)">
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Invoice</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Pelanggan</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Total</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Status</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Tanggal</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">File</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors duration-100" style="border-top:1px solid rgba(34,53,96,0.06)">

                    <td class="px-5 py-3">
                        <span class="font-mono font-semibold text-xs px-2.5 py-1 rounded-md" style="background:rgba(59,130,246,0.07);color:#3B82F6">
                            {{ $order->invoice_number }}
                        </span>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-navy flex items-center justify-center shrink-0">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-navy text-xs">{{ $order->user->name }}</p>
                                <p class="text-navy text-xs" style="opacity:0.4">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-3 font-semibold text-navy text-xs">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>

                    <td class="px-5 py-3">
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs font-semibold px-2.5 py-1 rounded-md outline-none cursor-pointer"
                                    style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb;color:#223560">
                                <option value="unpaid"     {{ $order->status == 'unpaid'     ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid"       {{ $order->status == 'paid'       ? 'selected' : '' }}>Paid</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed"  {{ $order->status == 'completed'  ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled"  {{ $order->status == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>

                    <td class="px-5 py-3 text-xs text-navy" style="opacity:0.5">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </td>

                    {{-- Kolom File Desain --}}
                    <td class="px-5 py-3">
                        @php
                            $files = $order->details->filter(fn($d) => !empty($d->image_detail));
                        @endphp
                        @if($files->isNotEmpty())
                            <div class="flex items-center gap-1 flex-wrap">
                                @foreach($files as $i => $detail)
                                <a href="{{ asset('storage/' . $detail->image_detail) }}" download
                                   title="Unduh file desain - {{ $detail->product->name }}"
                                   class="inline-flex items-center justify-center w-7 h-7 rounded-md transition-colors"
                                   style="background:rgba(16,185,129,0.08);color:#059669;text-decoration:none"
                                   onmouseover="this.style.background='rgba(16,185,129,0.2)'" onmouseout="this.style.background='rgba(16,185,129,0.08)'">
                                    <i class="fas fa-download" style="font-size:10px"></i>
                                </a>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-navy" style="opacity:0.3">—</span>
                        @endif
                    </td>

                    <td class="px-5 py-3">
                        <button type="button" onclick="openOrderModal({{ $order->id }}, false)"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-md cursor-pointer"
                                style="background:rgba(59,130,246,0.08);color:#3B82F6;border:none"
                                onmouseover="this.style.background='rgba(59,130,246,0.15)'" onmouseout="this.style.background='rgba(59,130,246,0.08)'">
                            <i class="fas fa-eye" style="font-size:10px"></i>
                            Detail
                        </button>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-navy" style="opacity:0.3">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i>
                        <p class="text-sm font-medium">Belum ada pesanan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="px-5 py-3 flex items-center justify-between" style="border-top:1px solid rgba(34,53,96,0.08)">
        <p class="text-xs text-navy" style="opacity:0.45">
            Halaman {{ $orders->currentPage() }} dari {{ $orders->lastPage() }}
        </p>
        <div class="flex items-center gap-1">
            @if($orders->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md text-xs text-navy" style="opacity:0.25;background:rgba(34,53,96,0.05)">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $orders->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-md text-xs text-navy"
                   style="background:rgba(34,53,96,0.06);text-decoration:none"
                   onmouseover="this.style.background='rgba(34,53,96,0.12)'" onmouseout="this.style.background='rgba(34,53,96,0.06)'">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            @foreach($orders->getUrlRange(max(1, $orders->currentPage()-2), min($orders->lastPage(), $orders->currentPage()+2)) as $page => $url)
                @if($page == $orders->currentPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md text-xs font-bold text-white" style="background:#3B82F6">{{ $page }}</span>
                @else
                    <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                       class="inline-flex items-center justify-center w-8 h-8 rounded-md text-xs font-semibold text-navy"
                       style="background:rgba(34,53,96,0.06);text-decoration:none"
                       onmouseover="this.style.background='rgba(34,53,96,0.12)'" onmouseout="this.style.background='rgba(34,53,96,0.06)'">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-md text-xs text-navy"
                   style="background:rgba(34,53,96,0.06);text-decoration:none"
                   onmouseover="this.style.background='rgba(34,53,96,0.12)'" onmouseout="this.style.background='rgba(34,53,96,0.06)'">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md text-xs text-navy" style="opacity:0.25;background:rgba(34,53,96,0.05)">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>
    @endif

</div>

{{-- Modal Detail Pesanan --}}
<div id="modalOrder" class="fixed inset-0 z-50" style="display:none;background:rgba(0,0,0,0.5);align-items:center;justify-content:center"
     onclick="if(event.target===this)closeOrderModal()">
    <div style="width:100%;max-width:900px;max-height:92vh;border:1px solid rgba(34,53,96,0.1);display:flex;flex-direction:column;background:#fff;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,0.3);margin:0 16px;overflow:hidden">

        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 24px;background:#223560;flex-shrink:0">
            <div>
                <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.5);margin:0 0 2px">Invoice</p>
                <h5 id="modalInvoiceNumber" style="font-size:15px;font-weight:700;color:white;margin:0">#—</h5>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <span id="modalStatusBadge" style="font-size:11px;font-weight:600;padding:4px 12px;border-radius:6px"></span>
                <button type="button" onclick="closeOrderModal()"
                        style="width:28px;height:28px;border-radius:6px;background:rgba(255,255,255,0.1);color:white;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <i class="fas fa-times" style="font-size:12px"></i>
                </button>
            </div>
        </div>

        <div style="overflow-y:auto;flex:1;padding:24px">
            <div id="modalLoading" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:64px 0;color:rgba(34,53,96,0.3)">
                <i class="fas fa-spinner fa-spin" style="font-size:28px;margin-bottom:12px"></i>
                <p style="font-size:14px;margin:0">Memuat data...</p>
            </div>
            <div id="modalContent" style="display:none">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid rgba(34,53,96,0.08)">
                    <div>
                        <p style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:rgba(34,53,96,0.4);margin:0 0 6px">Ditagihkan Kepada</p>
                        <p id="modalUserName" style="font-weight:700;color:#223560;font-size:13px;margin:0"></p>
                        <p id="modalUserEmail" style="font-size:11px;color:rgba(34,53,96,0.5);margin:2px 0 0"></p>
                        <p id="modalUserPhone" style="font-size:11px;color:rgba(34,53,96,0.5);margin:2px 0 0"></p>
                    </div>
                    <div style="text-align:right">
                        <p style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:rgba(34,53,96,0.4);margin:0 0 6px">Tanggal Order</p>
                        <p id="modalDate" style="font-weight:700;color:#223560;font-size:13px;margin:0"></p>
                        <p id="modalTime" style="font-size:11px;color:rgba(34,53,96,0.5);margin:2px 0 0"></p>
                    </div>
                </div>

                <div style="overflow-x:auto;margin-bottom:20px">
                    <table style="width:100%;border-collapse:collapse;font-size:13px">
                        <thead>
                            <tr style="background:rgba(34,53,96,0.04)">
                                <th style="text-align:left;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Produk</th>
                                <th style="text-align:center;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Qty</th>
                                <th style="text-align:right;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Harga</th>
                                <th style="text-align:right;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;padding:10px 16px;color:rgba(34,53,96,0.5)">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItems"></tbody>
                        <tfoot>
                            <tr style="border-top:2px solid rgba(34,53,96,0.1)">
                                <td colspan="3" style="padding:12px 16px;text-align:right;font-weight:700;color:#223560;font-size:13px">Total Tagihan</td>
                                <td id="modalTotal" style="padding:12px 16px;text-align:right;font-weight:700;color:#3B82F6;font-size:13px"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div id="modalFooter" style="display:none;padding:12px 24px;border-top:1px solid rgba(34,53,96,0.08);justify-content:flex-end;flex-shrink:0">
            <a id="modalDetailLink" href="#" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;padding:8px 16px;border-radius:6px;color:#223560;background:rgba(34,53,96,0.08);text-decoration:none"
               onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
                <i class="fas fa-external-link-alt" style="font-size:11px"></i>
                Buka Halaman Penuh
            </a>
        </div>
    </div>
</div>

{{-- Modal Preview Gambar --}}
<div id="modalImgPreview" class="fixed inset-0 z-50" style="display:none;background:rgba(0,0,0,0.8);align-items:center;justify-content:center"
     onclick="if(event.target===this)this.style.display='none'">
    <div style="position:relative;max-width:700px;width:100%;margin:0 16px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <a id="imgPreviewDownload" href="#" download
               style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;padding:7px 16px;border-radius:6px;background:#3B82F6;color:#fff;text-decoration:none">
                <i class="fas fa-download" style="font-size:10px"></i> Unduh File
            </a>
            <button type="button" onclick="document.getElementById('modalImgPreview').style.display='none'"
                    style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;padding:7px 16px;border-radius:6px;background:rgba(255,255,255,0.15);color:#fff;border:none;cursor:pointer"
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                <i class="fas fa-times" style="font-size:10px"></i> Tutup
            </button>
        </div>
        <img id="imgPreviewSrc" src="" style="width:100%;max-height:78vh;object-fit:contain;border-radius:8px;display:block">
    </div>
</div>

<script>
    function closeOrderModal() {
        document.getElementById('modalOrder').style.display = 'none';
    }

    function openImagePreview(src) {
        document.getElementById('imgPreviewSrc').src = src;
        document.getElementById('imgPreviewDownload').href = src;
        document.getElementById('modalImgPreview').style.display = 'flex';
        document.getElementById('modalImgPreview').style.alignItems = 'center';
        document.getElementById('modalImgPreview').style.justifyContent = 'center';
    }

    function openOrderModal(orderId, scrollToFile) {
        var modal = document.getElementById('modalOrder');
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';

        document.getElementById('modalLoading').style.display = 'flex';
        document.getElementById('modalContent').style.display = 'none';
        document.getElementById('modalFooter').style.display = 'none';
        document.getElementById('modalInvoiceNumber').textContent = '#—';

        fetch('/admin/orders/' + orderId + '/detail')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                document.getElementById('modalInvoiceNumber').textContent = '#' + data.invoice_number;

                var statusConfig = {
                    unpaid:     { label: 'Belum Dibayar', bg: 'rgba(251,191,36,0.2)',  color: '#fbbf24', border: 'rgba(251,191,36,0.3)' },
                    paid:       { label: 'Lunas',         bg: 'rgba(16,185,129,0.2)',  color: '#34d399', border: 'rgba(16,185,129,0.3)' },
                    processing: { label: 'Diproses',      bg: 'rgba(59,130,246,0.2)',  color: '#93c5fd', border: 'rgba(59,130,246,0.3)' },
                    completed:  { label: 'Selesai',       bg: 'rgba(16,185,129,0.2)',  color: '#34d399', border: 'rgba(16,185,129,0.3)' },
                    cancelled:  { label: 'Dibatalkan',    bg: 'rgba(239,68,68,0.2)',   color: '#f87171', border: 'rgba(239,68,68,0.3)' },
                };
                var sc = statusConfig[data.status] || { label: data.status, bg: 'rgba(34,53,96,0.1)', color: '#223560', border: 'rgba(34,53,96,0.2)' };
                var badge = document.getElementById('modalStatusBadge');
                badge.textContent = sc.label;
                badge.style.background = sc.bg;
                badge.style.color = sc.color;
                badge.style.border = '1px solid ' + sc.border;

                document.getElementById('modalUserName').textContent  = data.user.name;
                document.getElementById('modalUserEmail').textContent = data.user.email;
                document.getElementById('modalUserPhone').textContent = data.user.phone || '';
                document.getElementById('modalDate').textContent = data.created_date;
                document.getElementById('modalTime').textContent = data.created_time + ' WIB';

                var rows = '';
                data.details.forEach(function(d) {
                    var specs = d.specs_detail ? '<p style="font-size:11px;color:rgba(34,53,96,0.45);margin:2px 0 0">' + d.specs_detail + '</p>' : '';
                    var note  = d.note_detail  ? '<p style="font-size:11px;color:#f59e0b;margin:2px 0 0"><i class="fas fa-sticky-note" style="font-size:9px"></i> ' + d.note_detail + '</p>' : '';

                    var fileSection = '';
                    if (d.image_detail) {
                        fileSection = '<div style="margin-top:10px;padding:10px;border-radius:6px;background:rgba(59,130,246,0.04);border:1px solid rgba(59,130,246,0.12)">'
                            + '<p style="font-size:10px;font-weight:600;color:rgba(34,53,96,0.5);text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px">File Desain</p>'
                            + '<img src="' + d.image_detail + '" onclick="openImagePreview(\'' + d.image_detail + '\')" '
                            + 'style="width:100%;max-width:260px;height:130px;object-fit:cover;border-radius:6px;cursor:pointer;display:block;margin-bottom:8px;border:1px solid rgba(34,53,96,0.1)">'
                            + '<div style="display:flex;gap:6px">'
                            + '<button type="button" onclick="openImagePreview(\'' + d.image_detail + '\')" '
                            + 'style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:4px;font-size:11px;font-weight:600;padding:5px 8px;border-radius:5px;background:rgba(59,130,246,0.1);color:#3B82F6;border:none;cursor:pointer">'
                            + '<i class="fas fa-search-plus" style="font-size:9px"></i> Preview</button>'
                            + '<a href="' + d.image_detail + '" download style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:4px;font-size:11px;font-weight:600;padding:5px 8px;border-radius:5px;background:#3B82F6;color:#fff;text-decoration:none">'
                            + '<i class="fas fa-download" style="font-size:9px"></i> Unduh</a>'
                            + '</div></div>';
                    }

                    rows += '<tr style="border-top:1px solid rgba(34,53,96,0.06)">'
                          + '<td style="padding:12px 16px"><p style="font-weight:600;color:#223560;font-size:12px;margin:0">' + d.product_name + '</p>' + specs + note + fileSection + '</td>'
                          + '<td style="padding:12px 16px;text-align:center;font-size:12px;font-weight:600;color:#223560;vertical-align:top">' + d.quantity + '</td>'
                          + '<td style="padding:12px 16px;text-align:right;font-size:12px;color:rgba(34,53,96,0.6);vertical-align:top">' + d.price + '</td>'
                          + '<td style="padding:12px 16px;text-align:right;font-size:12px;font-weight:600;color:#223560;vertical-align:top">' + d.subtotal + '</td>'
                          + '</tr>';
                });

                document.getElementById('modalItems').innerHTML = rows;
                document.getElementById('modalTotal').textContent = data.total_price;
                document.getElementById('modalDetailLink').href = '/orders/' + orderId;

                document.getElementById('modalLoading').style.display = 'none';
                document.getElementById('modalContent').style.display = 'block';
                document.getElementById('modalFooter').style.display = 'flex';
            })
            .catch(function() {
                document.getElementById('modalLoading').innerHTML = '<p style="color:#ef4444;font-size:13px;text-align:center">Gagal memuat data.</p>';
            });
    }
</script>

@endsection