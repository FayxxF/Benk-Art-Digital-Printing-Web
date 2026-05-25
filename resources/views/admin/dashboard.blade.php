@extends('layouts.admin')

@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8">
    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-1">Dashboard Overview</p>
    <h2 class="text-2xl font-bold text-slate-800">Selamat Datang Kembali, Admin</h2>
</div>

{{-- STATS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @php
        $cards = [
            ['title' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($stats['income'], 0, ',', '.'), 'icon' => 'fa-coins', 'color' => 'bg-blue-600'],
            ['title' => 'Total Pesanan', 'value' => $stats['orders_count'], 'icon' => 'fa-shopping-bag', 'color' => 'bg-indigo-600'],
            ['title' => 'Total Produk', 'value' => $stats['products_count'], 'icon' => 'fa-box', 'color' => 'bg-emerald-600'],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="{{ $card['color'] }} rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <div class="p-2 bg-white/20 rounded-lg w-fit mb-4 backdrop-blur-sm">
                <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <p class="text-white/70 text-sm font-medium">{{ $card['title'] }}</p>
            <h3 class="text-2xl font-bold">{{ $card['value'] }}</h3>
        </div>
        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
    </div>
    @endforeach
</div>

{{-- PRODUK TERLARIS --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-slate-50">
        <h3 class="text-base font-bold text-slate-800">Produk Terlaris</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-[10px] uppercase text-slate-400 bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3">Harga</th>
                    <th class="px-6 py-3">Terjual</th>
                    <th class="px-6 py-3">Spesifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($stats['best_seller'] as $product)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                        <div>
                            <div class="font-semibold text-slate-800">{{ $product->name }}</div>
                            <div class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ $product->description }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-800">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-md bg-emerald-50 text-emerald-600 font-bold text-[10px]">{{ $product->sold_count }} pcs</span></td>
                    <td class="px-6 py-4">
                        @foreach ($product->specs ?? [] as $spec)
                            <div class="text-[10px] text-slate-600">{{ $spec['name'] }}: {{ implode(', ', array_column($spec['options'], 'value')) }}</div>
                        @endforeach
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- GRID: ASOSIASI RULE & INSIGHT --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- KIRI: TABEL ASOSIASI --}}
    <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50">
            <h3 class="text-base font-bold text-slate-800">Rekomendasi Bundling (Asosiasi)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[10px] uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="px-6 py-3">Produk A</th>
                        <th class="px-6 py-3 text-center">→</th>
                        <th class="px-6 py-3">Produk B</th>
                        <th class="px-6 py-3 text-center">Conf.</th>
                        <th class="px-6 py-3 text-center">Support</th>
                        <th class="px-6 py-3 text-center">Lift</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($stats['recommend_products'] as $rec)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $rec['product_a'] }}</td>
                        <td class="px-6 py-4 text-center text-slate-300"><i class="fas fa-arrow-right"></i></td>
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $rec['product_b'] }}</td>
                        <td class="px-6 py-4 text-center font-black text-slate-800">{{ $rec['percentage'] }}%</td>
                        <td class="px-6 py-4 text-center font-black text-slate-800">{{ $rec['support'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-center font-black text-slate-800">{{ $rec['lift'] ?? 0 }}x</td>
                        <td class="px-6 py-4">
                            @php $c = $rec['percentage'] > 70 ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700'; @endphp
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $c }}">
                                {{ $rec['percentage'] > 70 ? 'Strong' : 'Medium' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400 italic">Data belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- {{-- KANAN: INSIGHT --}}
    <div class="lg:col-span-1">
        <div class="bg-slate-800 rounded-2xl p-6 text-white h-full shadow-lg">
            <h3 class="text-base font-bold mb-4">Insight Analisis</h3>
            <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                Market Basket Analysis mendeteksi total <strong>{{ $totalPairsCount ?? 0 }} aturan</strong> kombinasi unik berdasarkan histori transaksi.
            </p>
            <div class="bg-white/10 p-4 rounded-xl border border-white/5">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 mb-1">Kombinasi Terpopuler</p>
                <p class="text-sm font-bold text-white">{{ $topCombination ?? '-' }}</p>
            </div>
        </div>
    </div> -->
</div>

{{-- PESANAN TERBARU --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800">Pesanan Terbaru</h3>
            <p class="text-[11px] text-slate-400">Daftar transaksi masuk terkini</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-bold uppercase tracking-widest text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-[10px] uppercase text-slate-400 bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Invoice</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($stats['recent_orders'] as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md text-[11px]">{{ $order->invoice_number }}</span>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $st = $order->status;
                            $cls = $st == 'paid' ? 'bg-emerald-50 text-emerald-700' : ($st == 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600');
                        @endphp
                        <span class="px-2 py-1 rounded-md border text-[10px] font-bold uppercase {{ $cls }}">{{ ucfirst($st) }}</span>
                    </td>
                    <td class="px-6 py-4 text-[11px] text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">Belum ada pesanan terbaru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
