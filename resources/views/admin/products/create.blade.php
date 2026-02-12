@extends('layouts.admin')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Tambah Produk Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Kategori</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Harga Dasar (Rp)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Stok Awal</label>
                            <input type="number" name="stock" class="form-control" value="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Foto Produk</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <h6 class="fw-bold">Spesifikasi Dinamis</h6>
                    <small class="text-muted d-block mb-3">Tambah opsi seperti "Bahan", "Ukuran", dll.</small>
                    
                    <div id="specs-container">
                        </div>

                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addSpecGroup()">
                        <i class="fas fa-plus"></i> Tambah Grup Spesifikasi
                    </button>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary px-4">Simpan Produk</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let specIndex = 0; // To keep track of groups (Material, Size...)

    function addSpecGroup() {
        const container = document.getElementById('specs-container');
        
        const html = `
            <div class="card mb-3 bg-light border" id="spec-group-${specIndex}">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between mb-2">
                        <input type="text" name="specs[${specIndex}][name]" class="form-control form-control-sm fw-bold" placeholder="Nama Grup (Misal: Bahan)" required>
                        <button type="button" class="btn btn-sm text-danger" onclick="removeGroup(${specIndex})">&times;</button>
                    </div>
                    
                    <div id="options-container-${specIndex}">
                        </div>
                    
                    <button type="button" class="btn btn-xs btn-link text-decoration-none" onclick="addOption(${specIndex})">
                        + Tambah Opsi
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        addOption(specIndex); // Add 1 empty option by default
        specIndex++;
    }

    function addOption(groupIndex) {
        const container = document.getElementById(`options-container-${groupIndex}`);
        const optionIndex = container.children.length; // Count existing options
        
        const html = `
            <div class="input-group input-group-sm mb-1">
                <input type="text" name="specs[${groupIndex}][options][${optionIndex}][value]" class="form-control" placeholder="Pilihan (Cth: Ivory)" required>
                <input type="number" name="specs[${groupIndex}][options][${optionIndex}][price]" class="form-control" placeholder="Tambahan Harga (0)" value="0">
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }

    function removeGroup(id) {
        document.getElementById(`spec-group-${id}`).remove();
    }
</script>
@endpush