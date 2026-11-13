<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'discount',
        'quantity',
        'sum',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function calcSum(float $price, float $discount, int $quantity): float
    {
        return ($price - ($price / 100 * $discount)) * $quantity;
    }
}
