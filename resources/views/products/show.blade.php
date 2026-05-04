@extends('layouts.app')

@section('content')

<div class="container py-5">
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    {{-- Gambar Produk --}}
    <div>
        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/500x500?text=No+Image' }}"
             alt="{{ $product->name }}"
             style="width:100%;border-radius:12px;object-fit:cover;max-height:420px">
    </div>

    {{-- Info & Form --}}
    <div>
        {{-- Badge kategori --}}
        <span style="font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;background:rgba(59,130,246,0.1);color:#3B82F6">
            {{ $product->category->name }}
        </span>

        <h2 style="font-size:22px;font-weight:700;color:#223560;margin:10px 0 6px">{{ $product->name }}</h2>
        <p id="displayPrice" style="font-size:22px;font-weight:700;color:#3B82F6;margin:0 0 10px">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>
        <p style="font-size:13px;color:rgba(34,53,96,0.55);margin:0 0 16px;line-height:1.6">{{ $product->description }}</p>

        <div style="border-top:1px solid rgba(34,53,96,0.08);margin-bottom:20px"></div>

        <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" id="basePrice" value="{{ $product->price }}">

            {{-- Spesifikasi --}}
            @if($product->specs && count($product->specs) > 0)
            <div style="margin-bottom:20px">
                <p style="font-size:13px;font-weight:700;color:#223560;margin:0 0 12px">Pilih Spesifikasi</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($product->specs as $index => $spec)
                    <div>
                        <label style="font-size:12px;font-weight:600;color:rgba(34,53,96,0.6);display:block;margin-bottom:5px">
                            {{ $spec['name'] }}
                        </label>
                        <select name="specs[{{ $spec['name'] }}]"
                                class="spec-select w-full px-3 py-2 rounded-md text-sm text-navy outline-none"
                                style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb;color:#223560"
                                onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'"
                                onchange="updatePrice()"
                                required>
                            <option value="" data-price="0">-- Pilih {{ $spec['name'] }} --</option>
                            @foreach($spec['options'] as $option)
                            <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}">
                                {{ $option['value'] }}
                                @if($option['price'] > 0)
                                    (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="border-top:1px solid rgba(34,53,96,0.08);margin-bottom:20px"></div>
            @endif

            {{-- Upload Desain --}}
            <div style="margin-bottom:14px">
                <label style="font-size:12px;font-weight:600;color:rgba(34,53,96,0.6);display:block;margin-bottom:5px">
                    Upload Desain <span style="font-weight:400;opacity:0.6">(Opsional)</span>
                </label>
                <input type="file" name="image_request" accept="image/*,.pdf"
                       class="w-full px-3 py-2 rounded-md text-sm text-navy outline-none"
                       style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb">
                <p style="font-size:10px;color:rgba(34,53,96,0.4);margin:4px 0 0">Format: JPG, PNG, PDF. Maks 5MB.</p>
            </div>

            {{-- Catatan --}}
            <div style="margin-bottom:14px">
                <label style="font-size:12px;font-weight:600;color:rgba(34,53,96,0.6);display:block;margin-bottom:5px">
                    Catatan Tambahan
                </label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 rounded-md text-sm text-navy outline-none resize-none"
                          style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
                          onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'"
                          placeholder="Contoh: Tolong warna dibuat lebih terang..."></textarea>
            </div>

            {{-- Qty + Tombol --}}
            <div class="flex items-end gap-3">
                <div style="width:100px">
                    <label style="font-size:12px;font-weight:600;color:rgba(34,53,96,0.6);display:block;margin-bottom:5px">Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" required
                           class="w-full px-3 py-2 rounded-md text-sm text-navy outline-none text-center"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
                           onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
                </div>
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 text-white font-semibold py-2.5 rounded-md transition-colors"
                        style="background:#223560;border:none;cursor:pointer;font-size:14px"
                        onmouseover="this.style.background='#1a2a4e'" onmouseout="this.style.background='#223560'">
                    <i class="fas fa-cart-plus"></i> Masuk Keranjang
                </button>
            </div>

        </form>
    </div>
</div>
</div>

{{-- SECTION INI DITAMBAH DI BAWAH CLOSING </div> CONTAINER UTAMA --}}
{{-- Rekomendasi Produk (Apriori) --}}
@if($recommendedProducts->count() > 0)
<div class="container py-4" style="border-top:1px solid rgba(34,53,96,0.08)">
    <div style="margin-bottom:16px">
        <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:rgba(34,53,96,0.4);margin:0 0 4px">Rekomendasi</p>
        <h4 style="font-size:18px;font-weight:700;color:#223560;margin:0">Sering Dibeli Bersama</h4>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px">
        @foreach($recommendedProducts as $rec)
        <div style="background:#fff;border-radius:10px;overflow:hidden;border:1px solid rgba(34,53,96,0.09);box-shadow:0 2px 8px rgba(34,53,96,0.06);transition:transform 0.18s ease"
             onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
            <a href="{{ route('products.show', $rec->id) }}" style="display:block;overflow:hidden">
                <img src="{{ $rec->image ? asset('storage/'.$rec->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}"
                     alt="{{ $rec->name }}"
                     style="width:100%;height:150px;object-fit:cover;display:block">
            </a>
            <div style="padding:10px 12px 12px">
                {{-- Badge confidence --}}
                <div style="display:inline-flex;align-items:center;gap:4px;background:rgba(59,130,246,0.08);border-radius:4px;padding:2px 7px;margin-bottom:6px">
                    <i class="fas fa-chart-line" style="font-size:8px;color:#3B82F6"></i>
                    <span style="font-size:10px;font-weight:600;color:#3B82F6">{{ round($rec->rec_confidence * 100) }}% relevan</span>
                </div>
                <p style="font-size:12px;font-weight:600;color:#223560;margin:0 0 4px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                    {{ $rec->name }}
                </p>
                <p style="font-size:13px;font-weight:700;color:#3B82F6;margin:0 0 8px">
                    Rp {{ number_format($rec->price, 0, ',', '.') }}
                </p>
                <a href="{{ route('products.show', $rec->id) }}"
                   style="display:block;text-align:center;font-size:11px;font-weight:700;padding:7px;border-radius:6px;background:#223560;color:white;text-decoration:none"
                   onmouseover="this.style.background='#1a2a4e'" onmouseout="this.style.background='#223560'">
                    Lihat Produk
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
<script>
    var basePrice = parseInt(document.getElementById('basePrice').value) || 0;

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updatePrice() {
        var extra = 0;
        document.querySelectorAll('.spec-select').forEach(function(sel) {
            var opt = sel.options[sel.selectedIndex];
            if (opt) extra += parseInt(opt.getAttribute('data-price') || 0);
        });
        document.getElementById('displayPrice').textContent = formatRupiah(basePrice + extra);
    }
</script>

@endsection

