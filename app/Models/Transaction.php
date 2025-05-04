<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'transaction_code',
        'amount',
        'payment_method',
        'transaction_status',
        'snap_token',
        'transaction_time',
        'settlement_time',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
