<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'discount_percentage',
        'discount_start_date',
        'discount_end_date',
        'stock',
        'image',
        'description',
        'specs',
    ];

    protected $casts = [
        'specs' => 'array',
        'discount_start_date' => 'datetime',
        'discount_end_date' => 'datetime',
    ];

    // Helper: Cek apakah diskon sedang aktif
    public function isDiscountActive()
    {
        if ($this->discount_percentage <= 0) return false;
        
        $now = now();
        $start = $this->discount_start_date;
        $end = $this->discount_end_date;

        if ($start && $now->lt($start)) return false;
        if ($end && $now->gt($end)) return false;

        return true;
    }

    // Helper: Ambil harga setelah diskon (sebelum spesifikasi)
    public function getDiscountedPrice()
    {
        if ($this->isDiscountActive()) {
            return $this->price - ($this->price * ($this->discount_percentage / 100));
        }
        return $this->price;
    }

    // Foreign Key ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // many to many
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // many to many
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Helper: untuk menghitung harga tambahan sesuai spesifikasi
    public function calculatePrice($selectedSpecs = []){
        $total = $this->getDiscountedPrice();
        
        // cek apabila tidak ada opsi spesifikasi atau spek yang dipilih = kosong 
        if(empty($this->specs) || empty($selectedSpecs)) {
            return $total;
        }

        // looping semua jenis spek yang tersedia
        foreach ($this->specs as $specs){
            // assign jenis spec ke var baru
            $groupName = $specs['name'];
            // cek apakah jenis spek tersebut terpilih
            if (isset($selectedSpecs[$groupName])){
                // apabila iya, assign ke var userChoice
                $userChoice = $selectedSpecs[$groupName];
                // looping opsi yang tersedia dari jenis spek tersebut 
                foreach ($specs['options'] as $option){
                    // cek apakah opsinya terpilih
                    if ($option['value'] == $userChoice){
                        // tambah nilai total dengan harga tambahan
                        $total += $option['price'];
                    }
                }
            }
        }
        return $total;
    }
}
