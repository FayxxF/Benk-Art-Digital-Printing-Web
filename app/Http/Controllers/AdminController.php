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

// Product Management 
    public function index(){
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create(){
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request){
        // validasi
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'specs' => 'nullable|array'
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        // save
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
        // Similar to store, but handle image replacement
        $data = $request->all();
        
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // 'specs' array from form maps to 'specs' column
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

    // --- ORDER MANAGEMENT ---

    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan diperbarui');
    }
}
