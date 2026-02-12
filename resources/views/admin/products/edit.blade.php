@extends('layouts.admin')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Produk: {{ $product->name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Kategori</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                    </div>
                </div>
                <div class="col-md-4 border-start">
                    <div class="mb-3 text-center">
                        <label class="d-block mb-2">Gambar Saat Ini</label>
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail mb-2" style="max-height: 150px">
                        <input type="file" name="image" class="form-control">
                    </div>
                    
                    <h6 class="fw-bold mt-4">Spesifikasi Dinamis</h6>
                    <div id="specs-container">
                        @if($product->specifications)
                            @foreach($product->specifications as $gIndex => $group)
                                <div class="card mb-3 bg-light border" id="spec-group-{{ $gIndex }}">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between mb-2">
                                            <input type="text" name="specs[{{ $gIndex }}][name]" value="{{ $group['name'] }}" class="form-control form-control-sm fw-bold">
                                            <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.parentElement.parentElement.remove()">&times;</button>
                                        </div>
                                        @foreach($group['options'] as $oIndex => $opt)
                                            <div class="input-group input-group-sm mb-1">
                                                <input type="text" name="specs[{{ $gIndex }}][options][{{ $oIndex }}][value]" value="{{ $opt['value'] }}" class="form-control">
                                                <input type="number" name="specs[{{ $gIndex }}][options][{{ $oIndex }}][price]" value="{{ $opt['price'] }}" class="form-control">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSpecGroup()">+ Tambah Grup</button>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-success px-5">Update Produk</button>
        </form>
    </div>
</div>
@endsection