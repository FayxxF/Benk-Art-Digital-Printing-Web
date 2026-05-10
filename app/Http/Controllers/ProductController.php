<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\AprioriService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->whereHas('category', fn($q) => $q->where('is_active', true))
            ->where('stock', '>', 0);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
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

        // Apriori recommendation
        $apriori         = new AprioriService(minSupport: 0.1, minConfidence: 0.3);
        $recommendations = $apriori->recommend($product->id, 4);

        // Load produk yang direkomendasikan
        $recommendedProducts = collect();
        if (!empty($recommendations)) {
            $productIds          = array_column($recommendations, 'product_id');
            $recommendedProducts = Product::with('category')
                ->whereIn('id', $productIds)
                ->whereHas('category', fn($q) => $q->where('is_active', true))
                ->get()
                ->map(function ($p) use ($recommendations) {
                    // Tambah info confidence & support ke produk
                    $rule = collect($recommendations)->firstWhere('product_id', $p->id);
                    $p->rec_confidence = $rule['confidence'] ?? 0;
                    $p->rec_support    = $rule['support'] ?? 0;
                    return $p;
                })
                ->sortByDesc('rec_confidence');
        }

        return view('products.show', compact('product', 'recommendedProducts'));
    }
}
