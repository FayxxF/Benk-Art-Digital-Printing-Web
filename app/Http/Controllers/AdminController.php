<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(){
        $stats = [
            'income' => Order::where('status', 'paid')->sum('total_price'),
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get()
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
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'specs' => 'nullable|array'
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
        $data = $request->all();

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->has('specs')) {
            $data['specs'] = $request->specs;
        }

        $product->update($data);
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
        $request->validate(['name' => 'required|unique:categories,name']);
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
            'name' => 'required|unique:categories,name,' . $category->id
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