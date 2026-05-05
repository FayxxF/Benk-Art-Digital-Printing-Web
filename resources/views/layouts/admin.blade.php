<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Benk Art</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#223560',
                        brand: '#3B82F6',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; border-radius: 10px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: all 0.15s ease;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .sidebar-link.active { background: rgba(255,255,255,0.12); color: #fff; }
        .sidebar-link i { width: 18px; text-align: center; font-size: 13px; }
    </style>
</head>
<body class="bg-gray-100 h-screen overflow-hidden">

{{-- Mobile Layout (Tetap Sama) --}}
<div class="md:hidden flex items-center justify-between px-4 py-3 bg-navy shadow-md">
    <span class="text-white font-bold text-lg">Benk Art Admin</span>
    <button onclick="toggleSidebar()" class="text-white text-xl"><i class="fas fa-bars"></i></button>
</div>

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar (Dibuat Fix) --}}
    <aside id="sidebar" class="w-64 bg-navy flex flex-col p-4 shrink-0 h-full">
        <div class="flex items-center gap-3 mb-6 px-2">
            <div class="w-9 h-9 rounded-full bg-brand flex items-center justify-center">
                <span class="text-white font-black text-sm">BA</span>
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-none">Benk Art</p>
                <p class="text-white/40 text-xs mt-0.5">Admin Panel</p>
            </div>
        </div>

        <p class="text-white/30 text-xs font-semibold uppercase tracking-widest px-2 mb-2">Menu</p>
        <nav class="flex flex-col gap-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Produk
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Kategori
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Pesanan
            </a>
        </nav>

        <div class="grow"></div>
        
        <div class="border-t border-white/10 my-4"></div>
        
        <div class="flex flex-col gap-1">
            <a href="{{ route('home') }}" class="sidebar-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Website
            </a>
            <form action="#" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-red-400 hover:text-red-300 hover:bg-red-900/20">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content (Scroll di sini saja) --}}
    <main class="flex-1 overflow-y-auto p-8">
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-md mb-6">
                <i class="fas fa-check-circle text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('hidden');
    }
</script>

@stack('scripts')
</body>
</html>