@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Riwayat Pesanan</h2>
    
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Filter Status
        </button>
        <ul class="dropdown-menu">
    <li>
        <a class="dropdown-item" href="{{ route('orders.index') }}">
            Semua Status
        </a>
    </li>
    @php
    // Define the available statuses and their readable labels
    $orderStatuses = [
        'unpaid'     => 'Belum Bayar',
        'paid'       => 'Lunas',
        'processing' => 'Proses Cetak',
        'completed'  => 'Selesai',
        'cancelled'  => 'Dibatalkan'
    ];
@endphp
    @foreach($orderStatuses as $value => $label)
        <li>
            <a class="dropdown-item {{ request('status') == $value ? 'active bg-primary text-white' : '' }}" 
               href="{{ route('orders.index', ['status' => $value]) }}">
                {{ $label }}
            </a>
        </li>
    @endforeach
</ul>
    </div>
</div>

<div class="row g-4">
    @foreach($orders as $order)
    <div class="col-md-3 col-6">
        <div class="card h-100 card-product">
            <div class="card-body d-flex flex-column">
                <span class="badge bg-secondary w-auto align-self-start mb-2">{{ $order->name }}</span>
                <h5 class="card-title">{{ $order->invoice_number }}</h5>
                <p class="text-secondary fw-bold mb-0">{{ $order->status }}
                <p class="text-primary fw-bold mb-0">Rp {{ number_format($order->total_price) }}
                
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary mt-auto w-100">
                    Detail Pesanan
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{-- {{ $products->links() }} --}}
</div>
@endsection