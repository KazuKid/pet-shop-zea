<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'original_price',
        'rating',
        'reviews_count',
        'stock',
        'sold_count',
        'image',
        'description',
        'tags',
        'is_new',
        'is_promo',
    ];

    protected function casts(): array
    {
        return [
            'tags'           => 'array',
            'price'          => 'integer',
            'original_price' => 'integer',
            'rating'         => 'float',
            'reviews_count'  => 'integer',
            'stock'          => 'integer',
            'sold_count'     => 'integer',
            'is_new'         => 'boolean',
            'is_promo'       => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Accessor: combine category data into response */
    public function toArray(): array
    {
        $array = parent::toArray();
        if ($this->relationLoaded('category') && $this->category) {
            $array['category']       = $this->category->slug;
            $array['category_label'] = $this->category->name;
        }
        if ($this->relationLoaded('images')) {
            $array['images'] = $this->images->pluck('image_url')->toArray();
        }
        return $array;
    }
}
