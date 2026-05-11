<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'gallery_images' => 'array',
        'is_vegan' => 'boolean',
        'in_stock' => 'boolean',
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'quantity' => 'integer',
        'is_popular' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brandData()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function usages()
    {
        return $this->hasMany(ProductUsage::class);
    }

    public function nutrition()
    {
        return $this->hasMany(ProductNutrition::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
