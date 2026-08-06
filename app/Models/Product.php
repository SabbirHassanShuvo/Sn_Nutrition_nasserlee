<?php

namespace App\Models;

use App\Models\AffiliateLink;
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

    protected $appends = [
        'offer_expiry',
    ];

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    public function getPriceAttribute($value)
    {
        // Try to fetch active campaign/offer
        $activeOffer = $this->offers()
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>', now());
            })
            ->first();

        if ($activeOffer && $activeOffer->discount_percent > 0) {
            return round($value - ($value * $activeOffer->discount_percent / 100), 2);
        }

        return $value;
    }

    public function getOldPriceAttribute($value)
    {
        $activeOffer = $this->offers()
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>', now());
            })
            ->first();

        if ($activeOffer && $activeOffer->discount_percent > 0) {
            return isset($this->attributes['price']) ? (float) $this->attributes['price'] : null;
        }

        return $value ? (float) $value : null;
    }

    public function getDiscountPercentAttribute($value)
    {
        $activeOffer = $this->offers()
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>', now());
            })
            ->first();

        if ($activeOffer && $activeOffer->discount_percent > 0) {
            return (float) $activeOffer->discount_percent;
        }

        return $value ? (float) $value : 0;
    }

    public function getOfferExpiryAttribute()
    {
        $activeOffer = $this->offers()
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>', now());
            })
            ->first();

        return $activeOffer && $activeOffer->expire_date ? $activeOffer->expire_date->toIso8601String() : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brandData()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
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

    public function affiliateLinks()
    {
        return $this->hasMany(AffiliateLink::class);
    }
}
