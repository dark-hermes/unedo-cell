<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'code',
        'description',
    ];

    protected $appends = [
        'image_url',
        'products_count',
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

    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
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
