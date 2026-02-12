<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    // instansiasi
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        // validasi
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'image_request' => 'nullable|image|max:5120',
        ]);

        // memanggil fungsi cartservice tambah keranjang 
        $this->cartService->addToCart(Auth::user(), $request->all(), $request->file('image_request'));

        return redirect()->route('cart.index')->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function destroy($id)
    {
        $this->cartService->removeItem($id, Auth::id());
        return back()->with('success', 'Item berhasil dihapus.');
    }
}