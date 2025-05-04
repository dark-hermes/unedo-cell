<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->type === 'image' && $this->value && filter_var($this->value, FILTER_VALIDATE_URL)) {
            return $this->value;
        } elseif ($this->type === 'image' && $this->value) {
            return asset('storage/' . $this->value);
        } else {
            return "https://placehold.co/600x400?text=No+Image";
        }
    }
}
