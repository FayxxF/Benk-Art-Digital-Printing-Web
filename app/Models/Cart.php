<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'image_request',
        'description_request',
        'specs_request',
    ];

    protected $casts = [
        'specs_request' => 'array',
    ];
    
    // FK
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // FK
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
