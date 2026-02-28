<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService{
    protected $paymentService;

    // 1. Inject PaymentService here
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }    

    public function createOrder($user){
        $order = DB::transaction(function () use ($user){
            // 1. get cart
            $cartItems = $user->carts()->with('product')->get();

            if ($cartItems -> isEmpty()){
                throw new \Exception("Keranjang Belanja Kosong.");
            }

            // 2. itung total
            $grandTotal = 0;
            foreach ($cartItems as $item){
                $unitPrice = $item->product->calculatePrice($item->specs_request);
                $grandTotal += $unitPrice * $item->quantity;
            }

            // 3. create judul order
            $order = Order::create([
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
                'total_price' => $grandTotal,
                'status' => 'unpaid',
                'snap_token' => null,
            ]);

            // 4. create detail order
            foreach ($cartItems as $item) {
                $finalUnitPrice = $item->product->calculatePrice($item->specs_request);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $finalUnitPrice, // Save Historical Price
                    'specs_detail' => $item->specs_request,
                    'image_detail' => $item->image_request,
                    'note_detail' => $item->description_request,
                ]);
            }

            // 5. clear cart
            Cart::where('user_id', $user->id)->delete();
            return $order;
        });
        try{
            $snapToken = $this->paymentService->processCheckout($order);
            $order->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Error: ' . $e->getMessage());
        }
        return $order;
    }
}