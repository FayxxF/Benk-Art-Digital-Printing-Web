@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Tambah Kategori</div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required placeholder="Cth: Merchandise">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td>
                                <span class="badge bg-{{ $cat->is_active ? 'success' : 'danger' }}">
                                    {{ $cat->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.toggle', $cat->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">Switch Status</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection