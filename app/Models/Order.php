<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = [
        'new' => 'Новый',
        'shipment' => 'Отгрузка',
        'delivery' => 'Доставка',
        'issued' => 'Выдан',
        'cancelled' => 'Отменен',
    ];

    public const STATUS_ORDER = ['new', 'shipment', 'delivery', 'issued', 'cancelled'];

    protected $fillable = [
        'number',
        'client_id',
        'user_id',
        'total',
        'status',
        'order_date',
        'shipment_date',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'shipment_date' => 'date',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function canEditItems(): bool
    {
        return $this->status === 'new';
    }

    public function canChangeTo(string $newStatus): bool
    {
        if ($newStatus === $this->status) {
            return true;
        }

        if ($newStatus === 'cancelled') {
            return $this->status !== 'cancelled';
        }

        $current = array_search($this->status, self::STATUS_ORDER);
        $target = array_search($newStatus, self::STATUS_ORDER);

        if ($current === false || $target === false) {
            return false;
        }

        if ($this->status === 'cancelled') {
            return false;
        }

        return $target > $current;
    }

    public function recalculateTotal(): void
    {
        $this->total = $this->items()->sum('sum');
        $this->save();
    }
}
