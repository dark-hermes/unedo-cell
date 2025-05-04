<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'source',
        'note',
        'received_at',
    ];

    protected $appends = [
        'source_label',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'purchase' => 'Pembelian',
            'return' => 'Retur',
            'other' => 'Lainnya',
            default => 'Unknown',
        };
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
