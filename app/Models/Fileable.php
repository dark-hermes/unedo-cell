<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fileable extends Model
{
    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'file_path',
        'file_type',
    ];

    public function fileable()
    {
        return $this->morphTo();
    }
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
    public function getFileTypeAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }
}
