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
        if ((int) $order->user_id !== (int) Auth::id() && Auth::user()->role !== 'admin') {
        abort(403);
    }

        $order->load('details.product');
        return view('orders.show', compact('order'));
    }

    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id());

        // SEARCH LOGIC: If searching, search by invoice or product name
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('details.product', function($pq) use ($request) {
                      $pq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by status if set
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by Category
        if ($request->category) {
            $query->whereHas('details.product', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Filter by Date
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('orders.index', compact('orders', 'categories'));
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
