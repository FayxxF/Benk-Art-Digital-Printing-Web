<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cancel all orders after 24h of not being paid, and restore the stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // cari order yang udah expired
        $expiredOrders = Order::where('status', 'unpaid')->where('created_at', '<', Carbon::now()->subHours(24))->get();
        $count = 0;
        
        foreach ($expiredOrders as $order){
            DB::transaction(function () use ($order){
                // ngambil semua order detail
                $orderDetails = OrderDetail::where('order_id', $order->id)->get();
                // loop semua produk
                foreach ($orderDetails as $detail) {
                    $product = Product::find($detail->product_id);
                    
                    if ($product) {
                        $product->increment('stock', $detail->quantity);
                    }
                }

                // Update the status pesanan ke 'cancelled'
                $order->update(['status' => 'cancelled']);
            });
            $count++;
        }
        // output message ke teminal
        $this->info("Successfully cancelled {$count} expired orders and restored stock.");

        }
}
