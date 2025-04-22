<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Sluggable, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'price',
        'discount',
        'sku',
        'min_stock',
        'category_id',
    ];

    protected $appends = [
        'image_url',
        'price_after_discount',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        } elseif ($this->image) {
            return asset('storage/' . $this->image);
        } else {
            return "https://placehold.co/600x400?text=No+Image";
        }
    }

    public function getPriceAfterDiscountAttribute(): float
    {
        return $this->price - ($this->price * $this->discount / 100);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
