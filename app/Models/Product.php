<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'manufacturer',
        'photo',
        'price',
        'unit',
        'short_description',
        'full_description',
    ];

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, 'product_characteristic')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canDelete(): bool
    {
        return !$this->orderItems()->exists();
    }
}
