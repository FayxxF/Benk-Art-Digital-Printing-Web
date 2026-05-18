<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
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
            'image_request' => 'required|image|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        // validasi stok produk 
        $product = Product::find($request->product_id);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Hitung total kuantitas produk ini yang sudah ada di keranjang user
        $existingCartQty = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->sum('quantity');

        if (($existingCartQty + $request->quantity) > $product->stock) {
            return redirect()->back()->with('error', 'Maaf, jumlah pembelian melebihi stok yang tersedia (Tersisa: ' . $product->stock . ', di keranjang Anda: ' . $existingCartQty . ').');
        }
        
        // memanggil fungsi cartservice tambah keranjang 
        $this->cartService->addToCart(Auth::user(), $request->all(), $request->file('image_request'));

        return redirect()->route('products.index')->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function destroy($id)
    {
        $this->cartService->removeItem($id, Auth::id());
        return back()->with('success', 'Item berhasil dihapus.');
    }
}