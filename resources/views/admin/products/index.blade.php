@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest text-navy mb-1" style="opacity:0.45">Manajemen</p>
        <h2 class="text-3xl font-bold text-navy">Produk</h2>
    </div>
    <a href="{{ route('admin.products.create') }}"
       class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-md transition-colors duration-150"
       style="background:#3B82F6;text-decoration:none"
       onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
        <i class="fas fa-plus text-xs"></i>
        Tambah Produk
    </a>
</div>

{{-- Search & Filter Bar --}}
<form method="GET" action="{{ route('admin.products.index') }}">
<div class="bg-white rounded-lg shadow-sm p-4 mb-4 flex flex-col sm:flex-row gap-3" style="border:1px solid rgba(34,53,96,0.1)">

    {{-- Search Input --}}
    <div class="relative flex-1">
        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-navy text-xs" style="opacity:0.35"></i>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama produk..."
               class="w-full pl-9 pr-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
               style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
               onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'"
               onblur="this.style.borderColor='rgba(34,53,96,0.15)';this.style.background='#f8f9fb'">
    </div>

    {{-- Filter Kategori --}}
    <select name="category"
            class="px-4 py-2 rounded-md text-sm text-navy outline-none"
            style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb;min-width:160px"
            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    {{-- Tombol Cari --}}
    <button type="submit"
            class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-md transition-colors duration-150"
            style="background:#3B82F6"
            onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
        <i class="fas fa-search text-xs"></i>
        Cari
    </button>

    {{-- Reset --}}
    @if(request('search') || request('category'))
    <a href="{{ route('admin.products.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-md text-navy"
       style="background:rgba(34,53,96,0.08);text-decoration:none"
       onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
        <i class="fas fa-times text-xs"></i>
        Reset
    </a>
    @endif

</div>
</form>

{{-- Table Card --}}
<div class="bg-white rounded-lg shadow-sm overflow-hidden" style="border:1px solid rgba(34,53,96,0.1)">

    {{-- Info Row --}}
    <div class="px-5 py-2.5 flex items-center justify-between" style="border-bottom:1px solid rgba(34,53,96,0.06)">
        <p class="text-xs text-navy" style="opacity:0.45">
            Menampilkan
            <span class="font-semibold">{{ $products->firstItem() ?? 0 }}</span>–<span class="font-semibold">{{ $products->lastItem() ?? 0 }}</span>
            dari <span class="font-semibold">{{ $products->total() }}</span> produk
        </p>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:rgba(34,53,96,0.04)">
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Foto</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Nama Produk</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Kategori</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Harga</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Stok</th>
                    <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors duration-100" style="border-top:1px solid rgba(34,53,96,0.06)">

                    {{-- Foto --}}
                    <td class="px-5 py-3">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="rounded-md"
                             style="width:48px;height:48px;object-fit:cover">
                    </td>

                    {{-- Nama --}}
                    <td class="px-5 py-3">
                        <span class="font-semibold text-navy">{{ $product->name }}</span>
                    </td>

                    {{-- Kategori --}}
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-md text-navy" style="background:rgba(34,53,96,0.08)">
                            {{ $product->category->name }}
                        </span>
                    </td>

                    {{-- Harga --}}
                    <td class="px-5 py-3 font-semibold text-navy">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>

                    {{-- Stok --}}
                    <td class="px-5 py-3">
                        @if($product->stock > 10)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-md" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#10b981"></span>
                                {{ $product->stock }}
                            </span>
                        @elseif($product->stock > 0)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-md" style="background:#fffbeb;color:#92400e;border:1px solid #fcd34d">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#f59e0b"></span>
                                {{ $product->stock }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-md" style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#ef4444"></span>
                                Habis
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors duration-150"
                               style="background:rgba(34,53,96,0.08);color:#223560;text-decoration:none"
                               onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
                                <i class="fas fa-pencil-alt" style="font-size:10px"></i>
                                Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus produk ini?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg cursor-pointer"
                                        style="background:rgba(239,68,68,0.08);color:#dc2626;border:none"
                                        onmouseover="this.style.background='rgba(239,68,68,0.15)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                    <i class="fas fa-trash-alt" style="font-size:10px"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-navy" style="opacity:0.3">
                        <i class="fas fa-box-open text-4xl mb-3 block"></i>
                        <p class="text-sm font-medium">
                            @if(request('search') || request('category'))
                                Produk tidak ditemukan
                            @else
                                Belum ada produk
                            @endif
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="px-5 py-3 flex items-center justify-between" style="border-top:1px solid rgba(34,53,96,0.08)">
        <p class="text-xs text-navy" style="opacity:0.45">
            Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
        </p>
        <div class="flex items-center gap-1">

            {{-- Prev --}}
            @if($products->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs text-navy cursor-not-allowed" style="opacity:0.25;background:rgba(34,53,96,0.05)">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs text-navy transition-colors"
                   style="background:rgba(34,53,96,0.06);text-decoration:none"
                   onmouseover="this.style.background='rgba(34,53,96,0.12)'" onmouseout="this.style.background='rgba(34,53,96,0.06)'">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-bold text-white" style="background:#3B82F6">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-semibold text-navy transition-colors"
                       style="background:rgba(34,53,96,0.06);text-decoration:none"
                       onmouseover="this.style.background='rgba(34,53,96,0.12)'" onmouseout="this.style.background='rgba(34,53,96,0.06)'">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs text-navy transition-colors"
                   style="background:rgba(34,53,96,0.06);text-decoration:none"
                   onmouseover="this.style.background='rgba(34,53,96,0.12)'" onmouseout="this.style.background='rgba(34,53,96,0.06)'">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs text-navy cursor-not-allowed" style="opacity:0.25;background:rgba(34,53,96,0.05)">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif

        </div>
    </div>
    @endif

</div>

@endsection