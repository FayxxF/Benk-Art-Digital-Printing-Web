@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="mb-4">
    <p class="text-xs font-semibold uppercase tracking-widest text-navy mb-1" style="opacity:0.45">Manajemen Produk</p>
    <h2 class="text-3xl font-bold text-navy">Edit Produk</h2>
    <p class="text-sm text-navy mt-1" style="opacity:0.4">{{ $product->name }}</p>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left: Main Info --}}
    <div class="lg:col-span-2 flex flex-col gap-5">

        <div class="bg-white rounded-lg shadow-sm p-5" style="border:1px solid rgba(34,53,96,0.1)">
            <h5 class="text-base font-bold text-navy mb-4">Informasi Produk</h5>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Nama Produk</label>
                <input type="text" name="name" value="{{ $product->name }}" required
                       class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none"
                       style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                       onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Kategori</label>
                    <select name="category_id"
                            class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none"
                            style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ $product->price }}" required
                           class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                           onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 rounded-md text-sm text-navy outline-none resize-none"
                          style="border:1.5px solid rgba(34,53,96,0.15);background:#fff"
                          onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='rgba(34,53,96,0.15)'">{{ $product->description }}</textarea>
            </div>
        </div>

    </div>

    {{-- Right: Foto & Spesifikasi --}}
    <div class="flex flex-col gap-5">

        {{-- Foto --}}
        <div class="bg-white rounded-lg shadow-sm p-5" style="border:1px solid rgba(34,53,96,0.1)">
            <h5 class="text-base font-bold text-navy mb-4">Foto Produk</h5>
            <div class="flex flex-col items-center gap-3">
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="rounded-md w-full" style="max-height:160px;object-fit:cover">
                <div class="w-full">
                    <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Ganti Foto</label>
                    <input type="file" name="image"
                           class="w-full px-4 py-2 rounded-md text-xs text-navy outline-none"
                           style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">
                    <p class="text-xs text-navy mt-1" style="opacity:0.35">Kosongkan jika tidak ingin mengganti foto</p>
                </div>
            </div>
        </div>

        {{-- Spesifikasi --}}
        <div class="bg-white rounded-lg shadow-sm p-5" style="border:1px solid rgba(34,53,96,0.1)">
            <h5 class="text-base font-bold text-navy mb-1">Spesifikasi Dinamis</h5>
            <p class="text-xs text-navy mb-4" style="opacity:0.4">Edit opsi seperti "Bahan", "Ukuran", dll.</p>

            <div id="specs-container" class="flex flex-col gap-3 mb-3">
                @if($product->specs)
                    @foreach($product->specs as $gIndex => $group)
                    <div class="rounded-md p-3 spec-group" style="background:rgba(34,53,96,0.04);border:1px solid rgba(34,53,96,0.1)">
                        <div class="flex items-center gap-2 mb-2">
                            <input type="text" name="specs[{{ $gIndex }}][name]" value="{{ $group['name'] }}"
                                   class="flex-1 px-3 py-1.5 rounded-md text-xs font-semibold text-navy outline-none"
                                   placeholder="Nama Grup (Misal: Bahan)"
                                   style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">
                            <button type="button" onclick="this.closest('.spec-group').remove()"
                                    class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold"
                                    style="background:rgba(239,68,68,0.08);color:#dc2626;border:none;cursor:pointer">
                                &times;
                            </button>
                        </div>
                        <div class="spec-options flex flex-col gap-1.5 mb-2">
                            @foreach($group['options'] as $oIndex => $opt)
                            <div class="flex gap-2 spec-option-row">
                                <input type="text" name="specs[{{ $gIndex }}][options][{{ $oIndex }}][value]" value="{{ $opt['value'] }}"
                                       class="flex-1 px-3 py-1.5 rounded-md text-xs text-navy outline-none"
                                       placeholder="Pilihan (Cth: Ivory)"
                                       style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">
                                <input type="number" name="specs[{{ $gIndex }}][options][{{ $oIndex }}][price]" value="{{ $opt['price'] }}"
                                       class="w-24 px-3 py-1.5 rounded-md text-xs text-navy outline-none"
                                       placeholder="Harga +"
                                       style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">
                                <button type="button" onclick="this.closest('.spec-option-row').remove()"
                                        class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold"
                                        style="background:rgba(239,68,68,0.08);color:#dc2626;border:none;cursor:pointer"
                                        title="Hapus opsi">
                                    &times;
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addOption(this)"
                                class="w-full text-xs font-semibold px-3 py-1.5 rounded-md"
                                style="background:rgba(34,53,96,0.06);border:1.5px dashed rgba(34,53,96,0.2);cursor:pointer;color:#223560">
                            + Tambah Opsi
                        </button>
                    </div>
                    @endforeach
                @endif
            </div>

            <button type="button" onclick="addSpecGroup()"
                    class="w-full inline-flex items-center justify-center gap-2 text-xs font-semibold px-3 py-2 rounded-md"
                    style="border:1.5px dashed rgba(59,130,246,0.4);color:#3B82F6;background:rgba(59,130,246,0.04);cursor:pointer"
                    onmouseover="this.style.background='rgba(59,130,246,0.08)'" onmouseout="this.style.background='rgba(59,130,246,0.04)'">
                <i class="fas fa-plus text-xs"></i> Tambah Grup Spesifikasi
            </button>
        </div>

    </div>
</div>

{{-- Footer Actions --}}
<div class="flex items-center justify-end gap-3 mt-5">
    <a href="{{ route('admin.products.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2 rounded-md text-navy"
       style="background:rgba(34,53,96,0.08);text-decoration:none"
       onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
        Batal
    </a>
    <button type="submit"
            class="inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2 rounded-md"
            style="background:#3B82F6;border:none;cursor:pointer"
            onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
        <i class="fas fa-save text-xs"></i>
        Update Produk
    </button>
</div>

</form>

<script>
    var specGroupIndex = {{ $product->specs ? count($product->specs) : 0 }};

    function addSpecGroup() {
        var container = document.getElementById('specs-container');
        var gi = specGroupIndex++;
        var html = '<div class="rounded-md p-3 spec-group" style="background:rgba(34,53,96,0.04);border:1px solid rgba(34,53,96,0.1)">'
            + '<div class="flex items-center gap-2 mb-2">'
            + '<input type="text" name="specs[' + gi + '][name]" class="flex-1 px-3 py-1.5 rounded-md text-xs font-semibold text-navy outline-none" placeholder="Nama Grup (Misal: Bahan)" style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">'
            + '<button type="button" onclick="this.closest(\'.spec-group\').remove()" class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold" style="background:rgba(239,68,68,0.08);color:#dc2626;border:none;cursor:pointer">&times;</button>'
            + '</div>'
            + '<div class="spec-options flex flex-col gap-1.5 mb-2"></div>'
            + '<button type="button" onclick="addOption(this)" class="w-full text-xs font-semibold px-3 py-1.5 rounded-md" style="background:rgba(34,53,96,0.06);border:1.5px dashed rgba(34,53,96,0.2);cursor:pointer;color:#223560">+ Tambah Opsi</button>'
            + '</div>';
        container.insertAdjacentHTML('beforeend', html);
        // Auto tambah 1 opsi kosong
        var newGroup = container.lastElementChild;
        addOptionToContainer(newGroup.querySelector('.spec-options'), gi, 0);
    }

    function addOption(btn) {
        var group = btn.closest('.spec-group');
        var container = group.querySelector('.spec-options');
        var nameInput = group.querySelector('input[name*="[name]"]');
        var match = nameInput.name.match(/specs\[(\d+)\]/);
        var gi = match ? match[1] : specGroupIndex;
        var oi = container.children.length;
        addOptionToContainer(container, gi, oi);
    }

    function addOptionToContainer(container, gi, oi) {
        var html = '<div class="flex gap-2 spec-option-row">'
            + '<input type="text" name="specs[' + gi + '][options][' + oi + '][value]" class="flex-1 px-3 py-1.5 rounded-md text-xs text-navy outline-none" placeholder="Pilihan (Cth: Ivory)" style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">'
            + '<input type="number" name="specs[' + gi + '][options][' + oi + '][price]" class="w-24 px-3 py-1.5 rounded-md text-xs text-navy outline-none" placeholder="Harga +" value="0" style="border:1.5px solid rgba(34,53,96,0.15);background:#fff">'
            + '<button type="button" onclick="this.closest(\'.spec-option-row\').remove()" class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold" style="background:rgba(239,68,68,0.08);color:#dc2626;border:none;cursor:pointer" title="Hapus opsi">&times;</button>'
            + '</div>';
        container.insertAdjacentHTML('beforeend', html);
    }
</script>

@endsection