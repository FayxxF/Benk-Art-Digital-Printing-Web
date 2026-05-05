@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-navy">Riwayat Pesanan</h2>
        
        <div class="dropdown">
            <button class="btn btn-outline-secondary rounded-pill px-4" type="button" data-bs-toggle="dropdown">
                Filter Status
            </button>
            <ul class="dropdown-menu shadow-sm border-0">
                <li><a class="dropdown-item" href="{{ route('orders.index') }}">Semua Status</a></li>
                @foreach(['unpaid'=>'Belum Bayar', 'paid'=>'Lunas', 'processing'=>'Proses Cetak', 'completed'=>'Selesai', 'cancelled'=>'Dibatalkan'] as $value => $label)
                    <li><a class="dropdown-item {{ request('status') == $value ? 'active' : '' }}" href="{{ route('orders.index', ['status' => $value]) }}">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- List Style (Memanjang ke bawah) --}}
    <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-uppercase small text-muted">
                    <th class="px-4 py-3">Invoice</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="px-4 py-3 fw-bold">{{ $order->invoice_number }}</td>
                    <td class="px-4 py-3">
                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'primary' }} bg-opacity-10 text-{{ $order->status == 'completed' ? 'success' : 'primary' }} rounded-pill px-3">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-primary fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-end">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">Belum ada riwayat pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .text-navy { color: #223560; }
</style>
@endsection