<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'sku',
        'name',
        'quantity',
        'unit_price',
        'note',
    ];

    protected $appends = [
        'total_price',
    ];
    public function getTotalPriceAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
