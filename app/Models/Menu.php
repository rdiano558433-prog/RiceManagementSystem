<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'category',
        'price_per_kilo',
        'stock',
        'description',
    ];

    protected $casts = [
        'price_per_kilo' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}