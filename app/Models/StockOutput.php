<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOutput extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'output_date',
        'note',
        'reason',
        'user_id',
    ];

    protected $appends = [
        'reason_label',
    ];

    protected $casts = [
        'output_date' => 'datetime',
    ];

    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'sale' => 'Penjualan',
            'return' => 'Retur',
            'broken' => 'Rusak',
            'missing' => 'Hilang',
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
