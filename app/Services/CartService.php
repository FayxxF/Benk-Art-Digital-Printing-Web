<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Storage;

class CartService
{
    public function addToCart($user, array $data, $imageFile = null)
    {
        $imagePath = null;
        if ($imageFile) {
            // menyimpan file gambar di folder public
            $imagePath = $imageFile->store('uploads/designs', 'public');
        }

        return Cart::create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'specs_request' => $data['specs'] ?? null,
            'description_request' => $data['notes'] ?? null,
            'image_request' => $imagePath,
        ]);
    }

    public function removeItem($cartId, $userId)
    {
        $cart = Cart::where('id', $cartId)->where('user_id', $userId)->firstOrFail();
        
        if ($cart->image_request) {
            Storage::disk('public')->delete($cart->image_request);
        }

        return $cart->delete();
    }
}