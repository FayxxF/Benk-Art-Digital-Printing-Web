<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->whereHas('category', fn($q) => $q->where('is_active', true));

        // Filter by category id
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->category->is_active) {
            abort(404);
        }

        return view('products.show', compact('product'));
    }
}