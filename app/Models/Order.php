<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'menu_id',
        'quantity',
        'price_per_kilo',
        'total_cost',
        'status',
        'order_date',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'price_per_kilo' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}