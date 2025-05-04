<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Sluggable, SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'image',
        'description',
        'sale_price',
        'buy_price',
        'discount',
        'min_stock',
        'weight',
        'unit',
        'show',
        'category_id',
    ];

    protected $appends = [
        'image_url',
        'price_after_discount',
        'stock',
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
        return $this->sale_price - ($this->sale_price * $this->discount / 100);
    }

    public function getStockAttribute(): int
    {
        return $this->stockEntries()->sum('quantity') - $this->stockOutputs()->sum('quantity');
    }


    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }

    public function stockOutputs()
    {
        return $this->hasMany(StockOutput::class);
    }

    public function scopeWithCurrentStock($query)
    {
        return $query->select('products.*')
            ->selectRaw('
            (SELECT COALESCE(SUM(quantity), 0) FROM stock_entries WHERE product_id = products.id) as entries_sum,
            (SELECT COALESCE(SUM(quantity), 0) FROM stock_outputs WHERE product_id = products.id) as outputs_sum,
            (SELECT COALESCE(SUM(quantity), 0) FROM stock_entries WHERE product_id = products.id) - 
            (SELECT COALESCE(SUM(quantity), 0) FROM stock_outputs WHERE product_id = products.id) as current_stock
        ');
    }

    public function scopeIsInWishlist($query)
    {
        $userId = Auth::id();
        return $this->wishlists()
            ->where('user_id', $userId)
            ->exists();
    }

    public function wishlists()
    {
        return $this->hasMany(ProductWishlist::class);
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
