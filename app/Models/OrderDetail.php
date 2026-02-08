<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',          // Historical price
        'specs_detail', // JSON of options chosen
        'image_detail', // File path at time of order
        'note_detail',  // Notes at time of order
    ];

    protected $casts = [
        'specs_snapshot' => 'array',
    ];

    // FK
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // FK
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
