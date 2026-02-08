<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Kartu Nama',
            'Spanduk & Banner',
            'Stiker & Label',
            'Brosur & Flyer',
            'Undangan',
            'Merchandise (Mug/Pin)',
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'is_active' => true,
            ]);
        }
    }
}
