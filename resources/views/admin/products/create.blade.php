@extends('layouts.admin')

@section('content')

<style>
    /* Hide number input spinners */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>


{{-- Page Header --}}
<div class="mb-4">
    <p class="text-xs font-semibold uppercase tracking-widest text-navy mb-1" style="opacity:0.45">Manajemen Produk</p>
    <h2 class="text-3xl font-bold text-navy">Tambah Produk Baru</h2>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left: Main Info --}}
    <div class="lg:col-span-2 flex flex-col gap-5">

        {{-- Card: Info Utama --}}
        <div class="bg-white rounded-lg shadow-sm p-5" style="border:1px solid rgba(34,53,96,0.1)">
            <h5 class="text-base font-bold text-navy mb-4">Informasi Produk</h5>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Nama Produk</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                       style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                       onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'"
                       placeholder="Masukkan nama produk">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Kategori</label>
                    <select name="category_id" required
                            class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                            style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Harga Dasar (Rp)</label>
                    <input type="number" name="price" required
                           class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                           onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'"
                           placeholder="0">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all resize-none"
                          style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                          onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'"
                          placeholder="Deskripsi produk..."></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Stok Awal</label>
                    <input type="number" name="stock" value="100"
                           class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                           onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Foto Produk</label>
                    <input type="file" name="image" required
                           class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">
                </div>
            </div>
        </div>

    </div>

    {{-- Right: Spesifikasi --}}
    <div class="flex flex-col gap-5">

        {{-- Discount Settings --}}
        <div class="bg-white rounded-lg shadow-sm p-5" style="border:1px solid rgba(34,53,96,0.1)">
            <h5 class="text-base font-bold text-navy mb-1">Pengaturan Diskon</h5>
            <p class="text-xs text-navy mb-4" style="opacity:0.4">Kelola diskon promosi berdasarkan periode waktu.</p>

            <div class="mb-3">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Persentase Diskon (%)</label>
                <div class="relative flex items-center">
                    <input type="number" name="discount_percentage" id="discount_percentage" 
                           value="0" min="0" max="100"
                           class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#fff; padding-right: 2.5rem;"
                           placeholder="0"
                           onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'"
                           onkeydown="if(event.key==='-' || event.key==='e') event.preventDefault();"
                           oninput="if(value > 100) value = 100; if(value < 0) value = 0; updatePricePreview()">
                    <span class="absolute right-4 text-sm font-bold text-navy" style="opacity:0.4">%</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Tanggal Mulai</label>
                <input type="datetime-local" name="discount_start" id="discount_start"
                       class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                       style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                       onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Tanggal Berakhir</label>
                <input type="datetime-local" name="discount_end" id="discount_end"
                       class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none transition-all"
                       style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                       onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
            </div>

            <div class="p-4 rounded-lg" style="background:rgba(34,53,96,0.03);border:1px dashed rgba(34,53,96,0.1)">
                <p class="text-[10px] font-bold uppercase tracking-wider text-navy mb-2" style="opacity:0.5">Preview Harga</p>
                <div id="price-preview-container">
                    <p id="original-price-display" class="text-sm text-navy mb-1" style="opacity:0.5; text-decoration: line-through; display:none;">Rp 0</p>
                    <p id="discounted-price-display" class="text-xl font-bold text-navy">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-5" style="border:1px solid rgba(34,53,96,0.1)">
            <h5 class="text-base font-bold text-navy mb-1">Spesifikasi Dinamis</h5>
            <p class="text-xs text-navy mb-4" style="opacity:0.4">Tambah opsi seperti "Bahan", "Ukuran", dll.</p>

            <div id="specs-container" class="flex flex-col gap-3 mb-3"></div>

            <button type="button" onclick="addSpecGroup()"
                    class="w-full inline-flex items-center justify-center gap-2 text-xs font-semibold px-3 py-2 rounded-md transition-colors duration-150"
                    style="border:1.5px dashed rgba(59,130,246,0.4);color:#3B82F6;background:rgba(59,130,246,0.04)"
                    onmouseover="this.style.background='rgba(59,130,246,0.08)'" onmouseout="this.style.background='rgba(59,130,246,0.04)'">
                <i class="fas fa-plus text-xs"></i> Tambah Grup Spesifikasi
            </button>
        </div>

    </div>

</div>

{{-- Footer Actions --}}
<div class="flex items-center justify-end gap-3 mt-6">
    <a href="{{ route('admin.products.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2 rounded-md transition-colors duration-150 text-navy"
       style="background:rgba(34,53,96,0.08);text-decoration:none"
       onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
        Batal
    </a>
    <button type="submit"
            class="inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2 rounded-md transition-colors duration-150"
            style="background:#3B82F6"
            onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
        <i class="fas fa-save text-xs"></i>
        Simpan Produk
    </button>
</div>

</form>

@endsection

@push('scripts')
<script>
    let specIndex = 0;

    function addSpecGroup() {
        const container = document.getElementById('specs-container');
        const html = `
            <div class="rounded-md p-3" id="spec-group-${specIndex}" style="background:rgba(34,53,96,0.04);border:1px solid rgba(34,53,96,0.1)">
                <div class="flex items-center gap-2 mb-2">
                    <input type="text" name="specs[${specIndex}][name]"
                           class="flex-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-navy outline-none"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                           placeholder="Nama Grup (Misal: Bahan)" required>
                    <button type="button" onclick="removeGroup(${specIndex})"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold transition-colors"
                            style="background:rgba(239,68,68,0.08);color:#dc2626;border:none"
                            onmouseover="this.style.background='rgba(239,68,68,0.15)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                        &times;
                    </button>
                    
                </div>
                <div id="options-container-${specIndex}" class="flex flex-col gap-1.5 mb-2"></div>
                <button type="button" onclick="addOption(${specIndex})"
                        class="text-xs font-semibold transition-colors"
                        style="color:#3B82F6;background:none;border:none;cursor:pointer;padding:0">
                    + Tambah Opsi
                </button>
                
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        addOption(specIndex);
        specIndex++;
    }

    function addOption(groupIndex) {
    const container = document.getElementById(`options-container-${groupIndex}`);
    const optionIndex = container.children.length;
    const html = `
        <div class="flex gap-2 spec-option-row">
            <input type="text" name="specs[${groupIndex}][options][${optionIndex}][value]"
                   class="flex-1 px-3 py-1.5 rounded-lg text-xs text-navy outline-none"
                   style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                   placeholder="Pilihan (Cth: Ivory)" required>
            <input type="number" name="specs[${groupIndex}][options][${optionIndex}][price]"
                   class="w-24 px-3 py-1.5 rounded-lg text-xs text-navy outline-none"
                   style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                   placeholder="Harga +" value="0">
            <button type="button" onclick="this.closest('.spec-option-row').remove()"
                    class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold"
                    style="background:rgba(239,68,68,0.08);color:#dc2626;border:none;cursor:pointer"
                    title="Hapus opsi">
                &times;
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

    function removeGroup(id) {
        document.getElementById(`spec-group-${id}`).remove();
    }

    function updatePricePreview() {
        const basePrice = parseFloat(document.querySelector('input[name="price"]').value) || 0;
        const discount = parseFloat(document.getElementById('discount_percentage').value) || 0;
        
        const originalPriceDisplay = document.getElementById('original-price-display');
        const discountedPriceDisplay = document.getElementById('discounted-price-display');

        if (discount > 0) {
            const finalPrice = basePrice - (basePrice * (discount / 100));
            originalPriceDisplay.style.display = 'block';
            originalPriceDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(basePrice);
            discountedPriceDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalPrice);
            discountedPriceDisplay.classList.add('text-red-600');
            discountedPriceDisplay.classList.remove('text-navy');
        } else {
            originalPriceDisplay.style.display = 'none';
            discountedPriceDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(basePrice);
            discountedPriceDisplay.classList.remove('text-red-600');
            discountedPriceDisplay.classList.add('text-navy');
        }
    }

    // Initialize preview
    document.addEventListener('DOMContentLoaded', function() {
        updatePricePreview();
        // Listen to base price changes too
        document.querySelector('input[name="price"]').addEventListener('input', updatePricePreview);
    });
</script>
@endpush