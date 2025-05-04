<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'note',
    ];

    protected $appends = [
        'subtotal',
    ];

    public function getSubtotalAttribute(): float
    {
        return $this->product->price_after_discount * $this->quantity;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
