@extends('layouts.app')

@section('content')

<style>
    .product-card { transition: transform 0.18s ease, box-shadow 0.18s ease; }
    .product-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(34,53,96,0.12) !important; }
    .product-card:hover .card-btn { background: #1a2a4e !important; }
    .cat-pill { transition: all 0.15s ease; }
    .cat-pill:hover { opacity: 0.85; }
</style>

<div class="container py-5">

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('products.index') }}" id="filterForm">
    <div style="background:#fff;border:1.5px solid rgba(34,53,96,0.15);border-radius:8px;display:flex;align-items:center;padding:6px 6px 6px 16px;margin-bottom:20px;box-shadow:0 2px 8px rgba(34,53,96,0.06)">
        <i class="fas fa-search" style="color:rgba(34,53,96,0.35);font-size:14px;margin-right:10px;flex-shrink:0"></i>
        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
               placeholder="Cari produk, kategori..."
               style="flex:1;border:none;outline:none;font-size:14px;color:#223560;background:transparent"
               onfocus="this.parentElement.style.borderColor='#3B82F6'" onblur="this.parentElement.style.borderColor='rgba(34,53,96,0.15)'">
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <button type="submit"
                style="background:#223560;color:white;border:none;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:600;cursor:pointer;flex-shrink:0"
                onmouseover="this.style.background='#1a2a4e'" onmouseout="this.style.background='#223560'">
            Cari
        </button>
    </div>
    </form>

    {{-- Filter Kategori --}}
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:20px">
        <span style="font-size:12px;font-weight:600;color:rgba(34,53,96,0.5);margin-right:4px">Kategori:</span>
        <a href="{{ route('products.index', request()->except('category')) }}"
           class="cat-pill"
           style="font-size:12px;font-weight:600;padding:5px 14px;border-radius:20px;text-decoration:none;{{ !request('category') ? 'background:#223560;color:white' : 'background:rgba(34,53,96,0.08);color:#223560;border:1px solid rgba(34,53,96,0.15)' }}">
            Semua
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('products.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}"
           class="cat-pill"
           style="font-size:12px;font-weight:600;padding:5px 14px;border-radius:20px;text-decoration:none;{{ request('category') == $cat->id ? 'background:#3B82F6;color:white' : 'background:rgba(34,53,96,0.08);color:#223560;border:1px solid rgba(34,53,96,0.15)' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    {{-- Result Info --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <p style="font-size:13px;color:rgba(34,53,96,0.5);margin:0">
            @if(request('search'))
                Hasil pencarian "<span style="font-weight:600;color:#223560">{{ request('search') }}</span>" —
            @endif
            <span style="font-weight:600;color:#223560">{{ $products->total() }}</span> produk ditemukan
        </p>
        @if(request('search') || request('category'))
        <a href="{{ route('products.index') }}"
           style="font-size:12px;font-weight:600;color:#3B82F6;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
            <i class="fas fa-times" style="font-size:10px"></i> Reset filter
        </a>
        @endif
    </div>

    {{-- Grid Produk --}}
    @if($products->count() > 0)
    <div id="productGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px">
        @foreach($products as $product)
        <div class="product-card" style="background:#fff;border-radius:10px;overflow:hidden;border:1px solid rgba(34,53,96,0.09);box-shadow:0 2px 8px rgba(34,53,96,0.06)">
            <a href="{{ route('products.show', $product->id) }}" style="display:block;overflow:hidden">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}"
                     alt="{{ $product->name }}"
                     style="width:100%;height:170px;object-fit:cover;display:block;transition:transform 0.3s ease"
                     onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
            </a>
            <div style="padding:10px 12px 12px">
                <p style="font-size:13px;font-weight:500;color:#223560;margin:0 0 5px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                    {{ $product->name }}
                </p>
                <p style="font-size:15px;font-weight:700;color:#223560;margin:0 0 4px">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p style="font-size:10px;font-weight:600;color:rgba(34,53,96,0.45);margin:0 0 10px;text-transform:uppercase;letter-spacing:0.04em">
                    {{ $product->category->name }}
                </p>
                <a href="{{ route('products.show', $product->id) }}"
                   class="card-btn"
                   style="display:block;text-align:center;font-size:12px;font-weight:700;padding:8px;border-radius:6px;background:#223560;color:white;text-decoration:none">
                    Detail & Pesan
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Loading indicator --}}
    <div id="loadingIndicator" style="display:none;text-align:center;padding:32px 0">
        <i class="fas fa-spinner fa-spin" style="font-size:24px;color:rgba(34,53,96,0.3)"></i>
        <p style="font-size:13px;color:rgba(34,53,96,0.4);margin:8px 0 0">Memuat lebih banyak produk...</p>
    </div>

    {{-- End of list --}}
    <div id="endOfList" style="display:none;text-align:center;padding:32px 0">
        <p style="font-size:13px;color:rgba(34,53,96,0.35);margin:0">Semua produk sudah ditampilkan</p>
    </div>

    @else
    <div style="text-align:center;padding:80px 0;color:rgba(34,53,96,0.3)">
        <i class="fas fa-search" style="font-size:48px;margin-bottom:16px;display:block"></i>
        <p style="font-size:16px;font-weight:600;margin:0 0 6px">Produk tidak ditemukan</p>
        <p style="font-size:13px;margin:0">Coba kata kunci atau kategori lain</p>
    </div>
    @endif

</div>

<script>
    var currentPage  = {{ $products->currentPage() }};
    var lastPage     = {{ $products->lastPage() }};
    var isLoading    = false;
    var baseUrl      = '{{ route('products.index') }}';
    var searchVal    = '{{ request('search') }}';
    var categoryVal  = '{{ request('category') }}';

    function buildUrl(page) {
        var params = [];
        if (searchVal)   params.push('search='   + encodeURIComponent(searchVal));
        if (categoryVal) params.push('category=' + encodeURIComponent(categoryVal));
        params.push('page=' + page);
        return baseUrl + '?' + params.join('&');
    }

    function loadMore() {
        if (isLoading || currentPage >= lastPage) return;
        isLoading = true;
        document.getElementById('loadingIndicator').style.display = 'block';

        fetch(buildUrl(currentPage + 1), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) { return res.text(); })
        .then(function(html) {
            var parser  = new DOMParser();
            var doc     = parser.parseFromString(html, 'text/html');
            var newCards = doc.querySelectorAll('#productGrid .product-card');
            var grid    = document.getElementById('productGrid');

            newCards.forEach(function(card) {
                grid.appendChild(card);
            });

            currentPage++;
            isLoading = false;
            document.getElementById('loadingIndicator').style.display = 'none';

            if (currentPage >= lastPage) {
                document.getElementById('endOfList').style.display = 'block';
            }
        })
        .catch(function() {
            isLoading = false;
            document.getElementById('loadingIndicator').style.display = 'none';
        });
    }

    // Trigger saat scroll mendekati bawah halaman
    window.addEventListener('scroll', function() {
        var scrollBottom = document.documentElement.scrollHeight - window.scrollY - window.innerHeight;
        if (scrollBottom < 300) {
            loadMore();
        }
    });
</script>

@endsection