<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'address',
        'latitude',
        'longitude',
        'recipient_name',
        'recipient_phone',
        'order_status',
        'receipt_number',
        'shipping_method',
        'shipping_cost',
        'note',
    ];

    protected $appends = [
        'total_price',
    ];

    public function getTotalPriceAttribute(): float
    {
        $orderItems = $this->orderItems()->get();
        $total = 0;
        foreach ($orderItems as $item) {
            $total += $item->unit_price * $item->quantity;
        }
        return $total + $this->shipping_cost;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
