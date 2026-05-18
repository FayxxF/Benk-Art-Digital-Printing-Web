<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Services\AprioriService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(){
        $bestSellers = Product::with('category')
            ->select('products.*')
            ->withSum(['orderDetails as sold_count' => function($q) {
                $q->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->whereIn('orders.status', ['paid', 'processing', 'completed']);
            }], 'quantity')
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();    
        
        $grafikBulanan = Order::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(id) as total_transaksi'),
                DB::raw('SUM(total_price) as total_pendapatan')
            )
            ->whereIn('status', ['paid', 'processing', 'completed'])
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $apriori = new AprioriService(minSupport: 0.01, minConfidence: 0.3);

        // 1. Dapatkan SEMUA rule kombinasi secara global, bukan per produk
        $rules = $apriori->generateRules();

        $formattedRecommendations = [];
        $topCombination = 'Belum ada data yang cukup';
        $totalPairsCount = count($rules);

        if (!empty($rules)) {
            // 2. Ambil misal 5 rule teratas saja untuk ditampilkan di Dashboard
            $topRules = array_slice($rules, 0, 5);

            // 3. Kumpulkan SEMUA Product ID dari rule tersebut agar kita cukup query 1x ke DB
            $productIds = [];
            foreach ($topRules as $rule) {
                $productIds = array_merge($productIds, $rule['antecedent'], $rule['consequent']);
            }
            $productIds = array_unique($productIds);

            // 4. Ambil nama produk dari database dan jadikan array [id => name]
            $productNames = Product::whereIn('id', $productIds)
                ->pluck('name', 'id');

            // 5. Format datanya agar sesuai dengan React Frontend Anda (product_a, product_b, percentage)
            foreach ($topRules as $rule) {
                // Karena Apriori bisa menghasilkan kombinasi [1, 2] => [3], kita ambil item pertamanya saja untuk disederhanakan di UI
                $idA = $rule['antecedent'][0] ?? null;
                $idB = $rule['consequent'][0] ?? null;

                $formattedRecommendations[] = [
                    'product_a' => $productNames[$idA] ?? 'Produk Dihapus',
                    'product_b' => $productNames[$idB] ?? 'Produk Dihapus',
                    'percentage' => round($rule['confidence'] * 100) // Ubah 0.85 jadi 85
                ];
            }

            // 6. Set Top Combination untuk KPI Card
            if (count($formattedRecommendations) > 0) {
                $topCombination = $formattedRecommendations[0]['product_a'] . ' + ' . $formattedRecommendations[0]['product_b'];
            }
        }


        $stats = [
            'income' => Order::whereIn('status', ['paid', 'completed'])->sum('total_price')
            - Order::where('status', 'cancelled')->sum('total_price'),
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'best_seller' => $bestSellers,
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
            'grafik_bulanan' => $grafikBulanan,
            'recommend_products' => $formattedRecommendations,
            'top_combination' => $topCombination,
            'total_pairs_count' => $totalPairsCount
        ];
        return view('admin.dashboard', compact('stats'));
    }

    // =========================================
    // PRODUCT MANAGEMENT
    // =========================================

    public function index(Request $request){
        $query = Product::with('category')->latest();

        // Filter pencarian nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Pagination 8 per halaman, bawa query string (search & category)
        $products = $query->paginate(8)->withQueryString();

        // Kirim kategori untuk dropdown filter
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(){
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric|min:0',
            'image' => 'required|image|max:51200',
            'specs' => 'nullable|array',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_start_date' => 'nullable|date',
            'discount_end_date'   => 'nullable|date|after_or_equal:discount_start_date',
            'description' => 'required|string',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath,
            'specs' => $request->specs,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'discount_start_date' => $request->discount_start_date,
            'discount_end_date'   => $request->discount_end_date,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk Berhasil Dibuat!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:51200',
            'specs' => 'nullable|array',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_start_date' => 'nullable|date',
            'discount_end_date'   => 'nullable|date|after_or_equal:discount_start_date',
            'description' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->name                = $request->name;
        $product->category_id         = $request->category_id;
        $product->price               = $request->price;
        $product->stock               = $request->stock ?? $product->stock;
        $product->description         = $request->description;
        $product->specs               = $request->specs;
        $product->discount_percentage = $request->discount_percentage ?? 0;
        $product->discount_start_date = $request->discount_start_date;
        $product->discount_end_date   = $request->discount_end_date;
        
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Produk diupdate');
    }
    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->image);
        $product->delete();
        return back()->with('success', 'Produk Berhasil Dihapus');
    }

    // =========================================
    // ORDER MANAGEMENT
    // =========================================

     public function orders(Request $request)
    {
        $query = Order::with('user')->latest();
 
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }
 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
 
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Export CSV
        if ($request->get('export') === 'csv') {
            $orders = $query->with('details.product')->get();
            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="pesanan.csv"',
            ];
            $callback = function() use ($orders) {
                $file = fopen('php://output', 'w');
                // BOM untuk Excel agar UTF-8 terbaca benar
                fputs($file, "\xEF\xBB\xBF");
                // Header kolom
                fputcsv($file, [
                    'Invoice',
                    'Pelanggan',
                    'Email',
                    'Produk',
                    'Qty',
                    'Spesifikasi',
                    'Deskripsi',
                    'Harga Satuan',
                    'Subtotal Produk',
                    'Total Order',
                    'Status',
                    'Tanggal',
                ], ';');
                foreach ($orders as $order) {
                    foreach ($order->details as $detail) {
                        $specsString = collect($detail->specs_detail)
                        ->map(fn($val, $key) => "$key: $val")
                        ->implode(' · ');
                        fputcsv($file, [
                            $order->invoice_number,
                            $order->user->name,
                            $order->user->email,
                            $detail->product->name ?? '-',
                            $detail->quantity,
                            $specsString,
                            $detail->note_detail,
                            $detail->price,
                            $detail->price * $detail->quantity,
                            $order->total_price,
                            $order->status,
                            $order->created_at->format('d/m/Y H:i'),
                        ], ';');
                    }
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
 
        $orders = $query->paginate(10)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    // Order Detail untuk Admin
      public function orderDetail(Order $order)
    {
        $order->load('user', 'details.product');
        return response()->json([
            'invoice_number' => $order->invoice_number,
            'status'         => $order->status,
            'total_price'    => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
            'created_date'   => $order->created_at->format('d F Y'),
            'created_time'   => $order->created_at->format('H:i'),
            'user' => [
                'name'  => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone ?? null,
            ],
            'details' => $order->details->map(fn($d) => [
                'product_name'   => $d->product->name,
                'quantity'       => $d->quantity,
                'price'          => 'Rp ' . number_format($d->price, 0, ',', '.'),
                'subtotal'       => 'Rp ' . number_format($d->price * $d->quantity, 0, ',', '.'),
                'specs_detail'   => $d->specs_detail
                    ? collect($d->specs_detail)->map(fn($v, $k) => "$k: $v")->implode(' · ')
                    : null,
                'note_detail'    => $d->note_detail ?? null,
                'image_detail'   => $d->image_detail ? asset('storage/' . $d->image_detail) : null,
                'image_filename' => $d->image_detail ? basename($d->image_detail) : null,
            ]),
        ]);
    }
 
 
    public function updateStatus(Request $request, Order $order)
    {
        // validasi
        if ($request -> status == 'cancelled' && $order->status != 'cancelled'){
            // ngambil semua detail order per produk
            $orderDetails = OrderDetail::where('order_id', $order->id)->get();
            // restore stok per produk
            foreach ($orderDetails as $detail) {
                $product = Product::find($detail->product_id);
                // cek kalo produk nya ada
                if ($product) {
                    // tambah kembali quantity nya
                    $product->increment('stock', $detail->quantity);
                }
            }
            // update status
        }
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan diperbarui');
    }

    // =========================================
    // CATEGORY MANAGEMENT
    // =========================================

   public function categories()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }
 
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create(['name' => $request->name, 'is_active' => true]);
        return back()->with('success', 'Kategori berhasil ditambah');
    }
 
    public function toggleCategory(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return back()->with('success', 'Status kategori diperbarui');
    }
 
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
        ]);
        $category->update(['name' => $request->name]);
        return back()->with('success', 'Kategori berhasil diupdate');
    }
 
    public function destroyCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus');
    }
}