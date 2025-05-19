<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    protected $fillable = [
        'user_id',
        'item_name',
        'item_type',
        'item_brand',
        'description',
        'price',
        'status',
        'fileable_id',
        'fileable_type',
    ];

    protected $appends = [
        'status_label',
    ];

    public function getFileUrlAttribute()
    {
        return $this->fileable ? $this->fileable->file_url : null;
    }

    public function getFileTypeAttribute()
    {
        return $this->fileable ? $this->fileable->file_type : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileable()
    {
        return $this->morphMany(Fileable::class, 'fileable');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        };
    }
}
