<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        // Seed some completed transactions for Apriori Recommendations
        $user = User::where('email', 'admin@gmail.com')->first();
        if ($user) {
            $p1 = \App\Models\Product::find(1);
            $p2 = \App\Models\Product::find(2);
            $p3 = \App\Models\Product::find(3);

            if ($p1 && $p2 && $p3) {
                // Create 5 completed transactions to satisfy minimum support & confidence rules
                for ($i = 1; $i <= 5; $i++) {
                    $order = \App\Models\Order::create([
                        'user_id' => $user->id,
                        'invoice_number' => 'INV-SEED-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                        'total_price' => 0,
                        'status' => 'completed',
                        'snap_token' => 'seed_snap_token_' . $i,
                    ]);

                    // Seed items
                    // Everyone buys P1 (Kartu Nama) and P3 (Stiker Vinyl) together
                    \App\Models\OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $p1->id,
                        'quantity' => 1,
                        'price' => $p1->price,
                        'specs_detail' => ['Bahan Kertas' => 'Art Carton 260gr (Standard)'],
                    ]);

                    \App\Models\OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $p3->id,
                        'quantity' => 1,
                        'price' => $p3->price,
                        'specs_detail' => ['Jenis Stiker' => 'Vinyl Putih Glossy'],
                    ]);

                    // Only Order 4 buys P2 (X-Banner)
                    if ($i === 4) {
                        \App\Models\OrderDetail::create([
                            'order_id' => $order->id,
                            'product_id' => $p2->id,
                            'quantity' => 1,
                            'price' => $p2->price,
                            'specs_detail' => ['Ukuran' => '60 x 160 cm'],
                        ]);
                        $order->update(['total_price' => $p1->price + $p2->price + $p3->price]);
                    } else {
                        $order->update(['total_price' => $p1->price + $p3->price]);
                    }
                }
            }
        }
    }
}
