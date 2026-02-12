<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        // Get 6 latest products for homepage
        $products = Product::latest()->take(6)->get();
        return view('layouts.app', compact('products'));
    }

    public function profile()
    {
        return view('pages.profile');
    }
}