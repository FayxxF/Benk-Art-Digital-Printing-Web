<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product 1: Kartu Nama (Category ID 1)
        Product::create([
            'category_id' => 1,
            'name' => 'Kartu Nama Standard (1 Box)',
            'price' => 35000, // Base Price
            'stock' => 100,
            'description' => 'Kartu nama berkualitas tajam. 1 Box isi 100 pcs.',
            'image' => 'kartunama.jpg', // Make sure to have a placeholder image later
            'specs' => [
                [
                    "name" => "Bahan Kertas",
                    "options" => [
                        ["value" => "Art Carton 260gr (Standard)", "price" => 0],
                        ["value" => "BW / Blues White (Premium)", "price" => 10000], // Adds 10k
                        ["value" => "Linen (Texture)", "price" => 15000] // Adds 15k
                    ]
                ],
                [
                    "name" => "Laminasi",
                    "options" => [
                        ["value" => "Tanpa Laminasi", "price" => 0],
                        ["value" => "Doff (Matte)", "price" => 20000],
                        ["value" => "Glossy (Kilap)", "price" => 20000]
                    ]
                ],
                [
                    "name" => "Sudut (Cutting)",
                    "options" => [
                        ["value" => "Lancip (Standard)", "price" => 0],
                        ["value" => "Rounded (Tumpul)", "price" => 5000]
                    ]
                ]
            ]
        ]);

        // Product 2: X-Banner (Category ID 2)
        Product::create([
            'category_id' => 2,
            'name' => 'X-Banner Wisuda / Promosi',
            'price' => 65000, // Base Price
            'stock' => 50,
            'description' => 'Banner berdiri penyangga bentuk X. Cocok untuk indoor.',
            'image' => 'xbanner.jpg',
            'specs' => [
                [
                    "name" => "Ukuran",
                    "options" => [
                        ["value" => "60 x 160 cm", "price" => 0],
                        ["value" => "80 x 180 cm", "price" => 25000] // Adds 25k
                    ]
                ],
                [
                    "name" => "Bahan Banner",
                    "options" => [
                        ["value" => "Flexi China 280gr (Standard)", "price" => 0],
                        ["value" => "Flexi Korea 440gr (Tebal)", "price" => 20000],
                        ["value" => "Albatros (Halus High Res)", "price" => 35000]
                    ]
                ]
            ]
        ]);

        // Product 3: Sticker (Category ID 3)
        Product::create([
            'category_id' => 3,
            'name' => 'Stiker Vinyl A3+ (Print & Cut)',
            'price' => 15000, // Per lembar A3
            'stock' => 500,
            'description' => 'Stiker anti air, sudah termasuk cutting pola (Kiss Cut).',
            'image' => 'sticker.jpg',
            'specs' => [
                [
                    "name" => "Jenis Stiker",
                    "options" => [
                        ["value" => "Vinyl Putih Glossy", "price" => 0],
                        ["value" => "Vinyl Putih Matte", "price" => 0],
                        ["value" => "Transparan", "price" => 2000]
                    ]
                ],
                [
                    "name" => "Finishing Potong",
                    "options" => [
                        ["value" => "Kiss Cut (Setengah Putus)", "price" => 0],
                        ["value" => "Die Cut (Putus Total)", "price" => 5000]
                    ]
                ]
            ]
        ]);

        // Product 4: Brosur / Flyer (Category ID 5)
        Product::create([
            'category_id' => 1,
            'name' => 'Brosur / Flyer A5 Full Color',
            'price' => 25000,
            'stock' => 300,
            'description' => 'Cetak brosur atau flyer A5 full color untuk promosi bisnis, event, dan produk.',
            'image' => 'brosur.jpg',
            'specs' => [
                [
                    "name" => "Bahan Kertas",
                    "options" => [
                        ["value" => "HVS 100gr", "price" => 0],
                        ["value" => "Art Paper 120gr", "price" => 5000],
                        ["value" => "Art Carton 190gr", "price" => 10000],
                    ]
                ],
                [
                    "name" => "Sisi Cetak",
                    "options" => [
                        ["value" => "1 Sisi", "price" => 0],
                        ["value" => "2 Sisi", "price" => 10000],
                    ]
                ]
            ]
        ]);

        // Product 5: Spanduk / Banner Outdoor (Category ID 5)
        Product::create([
            'category_id' => 2,
            'name' => 'Spanduk Outdoor Custom',
            'price' => 85000,
            'stock' => 80,
            'description' => 'Spanduk outdoor bahan flexi untuk promosi toko, acara, dan kebutuhan branding.',
            'image' => 'spanduk.jpg',
            'specs' => [
                [
                    "name" => "Ukuran",
                    "options" => [
                        ["value" => "1 x 1 meter", "price" => 0],
                        ["value" => "2 x 1 meter", "price" => 60000],
                        ["value" => "3 x 1 meter", "price" => 120000],
                    ]
                ],
                [
                    "name" => "Finishing",
                    "options" => [
                        ["value" => "Tanpa Mata Ayam", "price" => 0],
                        ["value" => "Mata Ayam / Ring", "price" => 10000],
                        ["value" => "Jahit Pinggir", "price" => 15000],
                    ]
                ]
            ]
        ]);
            }
}
