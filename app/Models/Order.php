<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_price',
        'status',
        'snap_token',
    ];
    
    // FK
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one to many
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // generate link whatsapp untuk konfirmasi pemesanan
    public function getWhatsappLinkAttribute()
    {
        $adminPhone = 'xxxx';
        
        $message  = "Halo Admin Benk Art, saya ingin konfirmasi pesanan:\n";
        $message .= "No Invoice: *{$this->invoice_number}*\n";
        $message .= "Total: Rp " . number_format($this->total_price, 0, ',', '.') . "\n";
        $message .= "Status: " . ucfirst($this->status) . "\n\n";
        $message .= "Mohon diproses. Terima kasih.";

        return "https://wa.me/{$adminPhone}?text=" . urlencode($message);
    }

}
