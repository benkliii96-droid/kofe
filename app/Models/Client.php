<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'note',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function canDelete(): bool
    {
        return !$this->orders()->exists();
    }
}
