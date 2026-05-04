@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="mb-4">
    <p class="text-xs font-semibold uppercase tracking-widest text-navy mb-1" style="opacity:0.45">Selamat Datang Kembali</p>
    <h2 class="text-3xl font-bold text-navy">Dashboard Overview</h2>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-4">

    {{-- Card: Total Pendapatan --}}
    <div class="relative overflow-hidden rounded-lg p-5 shadow-md hover:-translate-y-1 transition-transform duration-200" style="background:#3B82F6">
        <div class="absolute -top-5 -right-5 w-24 h-24 rounded-full pointer-events-none " style="background:rgba(255,255,255,0.1)"></div>
        <div class="absolute -bottom-8 -right-3 w-32 h-32 rounded-full pointer-events-none " style="background:rgba(255,255,255,0.1)"></div>
        <div class="flex items-start justify-between mb-4 relative">
            <div class="rounded-md p-2.5" style="background:rgba(255,255,255,0.2)">
                <i class="fas fa-coins text-white text-sm"></i>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-md" style="background:rgba(255,255,255,0.2);color:rgba(255,255,255,0.9)">Bulan ini</span>
        </div>
        <p class="text-sm font-medium mb-1 relative" style="color:rgba(255,255,255,0.75)">Total Pendapatan</p>
        <h3 class="text-2xl font-bold text-white relative">Rp {{ number_format($stats['income'], 0, ',', '.') }}</h3>
    </div>

    {{-- Card: Total Pesanan --}}
    <div class="relative overflow-hidden rounded-lg p-5 shadow-md hover:-translate-y-1 transition-transform duration-200" style="background:#3B82F6">
        <div class="absolute -top-5 -right-5 w-24 h-24 rounded-full pointer-events-none " style="background:rgba(255,255,255,0.1)"></div>
        <div class="absolute -bottom-8 -right-3 w-32 h-32 rounded-full pointer-events-none " style="background:rgba(255,255,255,0.1)"></div>
        <div class="flex items-start justify-between mb-4 relative">
            <div class="rounded-md p-2.5" style="background:rgba(255,255,255,0.2)">
                <i class="fas fa-shopping-bag text-white text-sm"></i>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-md" style="background:rgba(255,255,255,0.2);color:rgba(255,255,255,0.9)">Total</span>
        </div>
        <p class="text-sm font-medium mb-1" style="color:rgba(255,255,255,0.75)">Total Pesanan</p>
        <h3 class="text-2xl font-bold text-white">{{ $stats['orders_count'] }}</h3>
    </div>

    {{-- Card: Total Produk --}}
    <div class="relative overflow-hidden rounded-lg p-5 shadow-md hover:-translate-y-1 transition-transform duration-200" style="background:#3B82F6">
        <div class="absolute -top-5 -right-5 w-24 h-24 rounded-full pointer-events-none " style="background:rgba(255,255,255,0.1)"></div>
        <div class="absolute -bottom-8 -right-3 w-32 h-32 rounded-full pointer-events-none " style="background:rgba(255,255,255,0.1)"></div>
        <div class="flex items-start justify-between mb-4 relative">
            <div class="rounded-md p-2.5" style="background:rgba(255,255,255,0.2)">
                <i class="fas fa-box text-white text-sm"></i>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-md" style="background:rgba(255,255,255,0.2);color:rgba(255,255,255,0.9)">Aktif</span>
        </div>
        <p class="text-sm font-medium mb-1" style="color:rgba(255,255,255,0.75)">Total Produk</p>
        <h3 class="text-2xl font-bold text-white">{{ $stats['products_count'] }}</h3>
    </div>

    
</div>
{{-- Table: Produk Terlaris --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
<div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
    <h3 class="text-lg font-bold text-gray-900">Produk Terlaris</h3>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
            <tr>
                <th scope="col" class="px-6 py-3">Produk</th>
                <th scope="col" class="px-6 py-3">Harga Dasar</th>
                <th scope="col" class="px-6 py-3">Jumlah Terjual</th>
                <th scope="col" class="px-6 py-3">Spesifikasi & Varian</th>
            </tr>
        </thead>
        <tbody>
            {{-- Use forelse to automatically handle empty data --}}
            @forelse ($stats['best_seller'] as $product)
                <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                    
                    {{-- PRODUCT NAME & DESC --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden shrink-0">
                                {{-- Use Laravel's asset() helper for images --}}
                                <img 
                                    src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://via.placeholder.com/150?text=No+Image'"
                                />
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5 line-clamp-1 max-w-[200px]">
                                    {{ $product->description }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- BASE PRICE --}}
                    <td class="px-6 py-4 font-medium text-gray-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>

                    {{-- STOCK --}}
                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">
                            {{ $product->sold_count }} pcs
                        </span>
                    </td>

                    {{-- NESTED SPECS & OPTIONS --}}
                    <td class="px-6 py-4">
                        <div class="space-y-3">
                            {{-- Assuming 'specs' is cast to an array in your Product model --}}
                            @if(!empty($product->specs))
                                @foreach ($product->specs as $spec)
                                    <div>
                                        <span class="text-xs font-semibold text-gray-700 block mb-1">
                                            {{ $spec['name'] }}:
                                        </span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($spec['options'] as $opt)
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                    {{ $opt['value'] }}
                                                    
                                                    @if (isset($opt['price']) && $opt['price'] > 0)
                                                        <span class="ml-1 text-indigo-600 font-bold">
                                                            (+Rp {{ number_format($opt['price'], 0, ',', '.') }})
                                                        </span>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </td>

                </tr>
            @empty
                {{-- Empty State --}}
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                        Belum ada data produk terlaris.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

{{-- Recent Orders Table --}}
<div class="bg-white rounded-lg shadow-sm overflow-hidden mt-10" style="border:1px solid rgba(34,53,96,0.1)">

    {{-- Table Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-3" style="border-bottom:1px solid rgba(34,53,96,0.08)">
        <div>
            <h5 class="text-lg font-bold text-navy">Pesanan Terbaru</h5>
            <p class="text-xs mt-0.5 text-navy" style="opacity:0.4">Daftar transaksi masuk terkini</p>
        </div>
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-md transition-colors duration-150 self-start sm:self-auto bg-brand"
           style="text-decoration:none"
           onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
            <i class="fas fa-list text-xs"></i>
            Lihat Semua
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:rgba(34,53,96,0.04)">
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Invoice</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Pelanggan</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Total</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Status</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_orders'] as $order)
                <tr class="hover:bg-gray-50 transition-colors duration-100" style="border-top:1px solid rgba(34,53,96,0.06)">

                    {{-- Invoice --}}
                    <td class="px-5 py-3">
                        <span class="font-mono font-semibold text-xs text-brand px-2.5 py-1 rounded-lg" style="background:rgba(59,130,246,0.07)">
                            {{ $order->invoice_number }}
                        </span>
                    </td>

                    {{-- Pelanggan --}}
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-navy flex items-center justify-center shrink-0">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-navy">{{ $order->user->name }}</span>
                        </div>
                    </td>

                    {{-- Total --}}
                    <td class="px-5 py-3 font-semibold text-navy">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>

                    {{-- Status --}}
                    <td class="px-5 py-3">
                        @if($order->status == 'paid')
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-md" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#10b981"></span>
                                Lunas
                            </span>
                        @elseif($order->status == 'pending')
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-md" style="background:#fffbeb;color:#92400e;border:1px solid #fcd34d">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#f59e0b"></span>
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-md" style="background:rgba(34,53,96,0.07);color:#223560;border:1px solid rgba(34,53,96,0.15)">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:rgba(34,53,96,0.4)"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                        @endif
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-5 py-3 text-xs text-navy" style="opacity:0.45">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-navy" style="opacity:0.3">
                        <i class="fas fa-inbox text-4xl mb-3 block"></i>
                        <p class="text-sm font-medium">Belum ada pesanan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection