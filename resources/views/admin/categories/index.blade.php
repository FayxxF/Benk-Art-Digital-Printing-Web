@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest text-navy mb-1" style="opacity:0.45">Manajemen</p>
        <h2 class="text-3xl font-bold text-navy">Kategori</h2>
    </div>
    <button type="button" onclick="openModal('modalKategori')"
            class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-md transition-colors duration-150"
            style="background:#3B82F6"
            onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
        <i class="fas fa-plus text-xs"></i>
        Tambah Kategori
    </button>
</div>

{{-- Modal Tambah --}}
<div id="modalKategori" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background:rgba(0,0,0,0.35)">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6" style="border:1px solid rgba(34,53,96,0.1)">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-base font-bold text-navy">Tambah Kategori</h5>
            <button type="button" onclick="closeModal('modalKategori')"
                    class="w-7 h-7 rounded-md flex items-center justify-center text-sm"
                    style="background:rgba(34,53,96,0.07);color:#223560;border:none;cursor:pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            @if($errors->any())
            <div class="text-xs text-red-600 mb-3 p-2 rounded-md" style="background:#fef2f2;border:1px solid #fecaca">
                {{ $errors->first('name') }}
            </div>
            @endif
            <div class="mb-4">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Nama Kategori</label>
                <input type="text" name="name" required autofocus
                       placeholder="Cth: Merchandise"
                       class="w-full px-3 py-2 rounded-md text-sm text-navy outline-none"
                       style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
                       onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'"
                       onblur="this.style.borderColor='rgba(34,53,96,0.15)';this.style.background='#f8f9fb'">
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="closeModal('modalKategori')"
                        class="flex-1 text-sm font-semibold px-4 py-2 rounded-md text-navy"
                        style="background:rgba(34,53,96,0.08);border:none;cursor:pointer"
                        onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-md"
                        style="background:#3B82F6;border:none;cursor:pointer"
                        onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
                    <i class="fas fa-plus text-xs"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEditKategori" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background:rgba(0,0,0,0.35)">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6" style="border:1px solid rgba(34,53,96,0.1)">
        <h5 class="text-base font-bold text-navy mb-4">Edit Kategori</h5>
        <form id="formEditKategori" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-xs font-semibold text-navy mb-1.5" style="opacity:0.6">Nama Kategori</label>
                <input type="text" id="editNamaKategori" name="name" required
                       class="w-full px-3 py-2 rounded-md text-sm text-navy outline-none"
                       style="border:1.5px solid rgba(34,53,96,0.15);background:#f8f9fb"
                       onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'"
                       onblur="this.style.borderColor='rgba(34,53,96,0.15)';this.style.background='#f8f9fb'">
            </div>
            <div style="display:flex;gap:8px">
                <button type="button" onclick="closeModal('modalEditKategori')"
                        style="flex:1;background:rgba(34,53,96,0.08);border:none;cursor:pointer;padding:8px 16px;border-radius:6px;font-size:14px;font-weight:600;color:#223560"
                        onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
                    Batal
                </button>
                <button type="submit"
                        style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:6px;background:#3B82F6;border:none;cursor:pointer;padding:8px 16px;border-radius:6px;font-size:14px;font-weight:600;color:white"
                        onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
                    <i class="fas fa-save" style="font-size:11px"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus --}}
<div id="modalHapusKategori" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background:rgba(0,0,0,0.35)">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6" style="border:1px solid rgba(34,53,96,0.1)">
        <div class="flex flex-col items-center text-center mb-5">
            <div class="w-12 h-12 rounded-md flex items-center justify-center mb-3" style="background:rgba(239,68,68,0.1)">
                <i class="fas fa-trash-alt" style="color:#dc2626;font-size:18px"></i>
            </div>
            <h5 class="text-base font-bold text-navy mb-1">Hapus Kategori?</h5>
            <p class="text-xs text-navy" style="opacity:0.5">Kategori <span id="hapusNamaKategori" class="font-semibold"></span> akan dihapus permanen dan tidak bisa dikembalikan.</p>
        </div>
        <div style="display:flex;gap:8px">
            <button type="button" onclick="closeModal('modalHapusKategori')"
                    style="flex:1;background:rgba(34,53,96,0.08);border:none;cursor:pointer;padding:8px 16px;border-radius:6px;font-size:14px;font-weight:600;color:#223560"
                    onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
                Batal
            </button>
            <form id="formHapusKategori" method="POST" style="flex:1">
                @csrf @method('DELETE')
                <button type="submit"
                        style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:6px;background:#dc2626;border:none;cursor:pointer;padding:8px 16px;border-radius:6px;font-size:14px;font-weight:600;color:white"
                        onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <i class="fas fa-trash-alt" style="font-size:11px"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow-sm overflow-hidden" style="border:1px solid rgba(34,53,96,0.1)">
    <div class="px-5 py-3" style="border-bottom:1px solid rgba(34,53,96,0.08)">
        <p class="text-xs text-navy" style="opacity:0.45">
            Total <span class="font-semibold">{{ count($categories) }}</span> kategori
        </p>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr style="background:rgba(34,53,96,0.04)">
                <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Nama Kategori</th>
                <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Total Produk</th>
                <th class="text-left text-xs font-semibold uppercase tracking-wider px-5 py-2.5 text-navy" style="opacity:0.5">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr class="hover:bg-gray-50 transition-colors duration-100" style="border-top:1px solid rgba(34,53,96,0.06)">

                {{-- Nama --}}
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center" style="background:rgba(59,130,246,0.1)">
                            <i class="fas fa-tag text-xs" style="color:#3B82F6"></i>
                        </div>
                        <span class="font-semibold text-navy">{{ $cat->name }}</span>
                    </div>
                </td>

                {{-- Total Produk --}}
                <td class="px-5 py-3">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md text-navy" style="background:rgba(34,53,96,0.07)">
                        {{ $cat->products_count }} produk
                    </span>
                </td>

                {{-- Aksi --}}
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-md transition-colors duration-150 cursor-pointer"
                                style="background:rgba(34,53,96,0.08);color:#223560;border:none"
                                onmouseover="this.style.background='rgba(34,53,96,0.15)'" onmouseout="this.style.background='rgba(34,53,96,0.08)'">
                            <i class="fas fa-pencil-alt" style="font-size:10px"></i>
                            Edit
                        </button>
                        <button type="button" onclick="openHapusModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-md transition-colors duration-150 cursor-pointer"
                                style="background:rgba(239,68,68,0.08);color:#dc2626;border:none"
                                onmouseover="this.style.background='rgba(239,68,68,0.15)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                            <i class="fas fa-trash-alt" style="font-size:10px"></i>
                            Hapus
                        </button>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-5 py-12 text-center text-navy" style="opacity:0.3">
                    <i class="fas fa-tags text-3xl mb-2 block"></i>
                    <p class="text-sm font-medium">Belum ada kategori</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    // Tutup modal kalau klik di luar
    ['modalKategori','modalEditKategori','modalHapusKategori'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) closeModal(id);
        });
    });

    // Auto buka modal tambah jika ada error validasi
    @if($errors->any())
        openModal('modalKategori');
    @endif

    function openEditModal(id, name) {
        document.getElementById('editNamaKategori').value = name;
        document.getElementById('formEditKategori').action = '/admin/categories/' + id;
        openModal('modalEditKategori');
        setTimeout(() => document.getElementById('editNamaKategori').focus(), 100);
    }

    function openHapusModal(id, name) {
        document.getElementById('hapusNamaKategori').textContent = '"' + name + '"';
        document.getElementById('formHapusKategori').action = '/admin/categories/' + id;
        openModal('modalHapusKategori');
    }
</script>

@endsection