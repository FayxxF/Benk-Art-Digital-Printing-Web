<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        User::create([
            'name' => 'Admin Benk Art',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123123'), // Login with this!
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // 2. Customer
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '089876543210',
        ]);

        // 3. Regular Customer (Usap)
        User::create([
            'name' => 'Usap Biasa',
            'email' => 'usap@gmail.com',
            'password' => Hash::make('123123123'),
            'role' => 'customer',
            'phone' => '081298765432',
        ]);
    }
}
