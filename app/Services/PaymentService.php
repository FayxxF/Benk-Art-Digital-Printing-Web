<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentService
{
    public function processCheckout($order){
        // Merchant Server Key
        Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'Mid-server-OfCZ53jYNXSoaSyWb_o5BAaY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [],
        ];
        
        $params = array(
            'transaction_details' => array(
            'order_id' => $order->invoice_number,
            'gross_amount' => $order->total_price,
            ),
            'customer_details' => array(
                'first_name' => $order->user->name,
                'last_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ),
        );

        return Snap::getSnapToken($params);
    }
    }