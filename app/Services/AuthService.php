<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Handle creating a new user (Register)
     */
    public function registerUser(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'role'     => 'customer', // Default role
        ]);
    }

    public function attemptLogin($credentials, $remember = false)
    {
        return Auth::attempt($credentials, $remember);
    }
}