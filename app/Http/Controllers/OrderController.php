<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService){
        $this->orderService = $orderService;
    }

    public function store(Request $request){
        try{
            $order = $this->orderService->createOrder(Auth::user());
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        // Security: Only allow owner or admin
        if ($order->user_id !== Auth::id() && Auth::user() ->role !== 'admin') {
            abort(403);
        }

        $order->load('details.product');
        return view('orders.show', compact('order'));
    }

    public function index(Request $request)
    {
        if($request->status){
            $orders = Order::where('status', $request->status)
            ->latest()
            ->paginate(10);
        }else{
        $orders = Order::where('user_id', auth()->id())
                    ->latest()
                    ->paginate(10);
        }
        $categories = Category::all();
        $products = Product::all();

        return view('orders.index', compact('orders', 'categories', 'products'));
    }

    public function paymentSuccess(Order $order)
    {
        // 1. Update the status
        if ($order->status === 'unpaid') {
            $order->update(['status' => 'paid']);
        }

        // 2. Redirect back to the orders list
        return redirect()->route('orders.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }
}
