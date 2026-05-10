<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        // Get 6 latest products for homepage
        $products = Product::where('stock', '>', 0)
                           ->latest()
                           ->take(6)
                           ->get();
        return view('home', compact('products'));
    }

    public function profile()
    {
        return view('auth.profile');
    }

        public function about()
    {
        return view('about');
    }
    
}