<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'tracking_code',
        'clicks_count',
        'conversions_count',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected $appends = ['tracking_url', 'conversion_rate'];

    public function getTrackingUrlAttribute()
    {
        $baseUrl = config('app.frontend_url', url('/'));
        // Assuming the product page URL structure is /products/{slug}
        $slug = $this->product ? $this->product->slug : $this->product_id;
        return "{$baseUrl}/products/{$slug}?ref={$this->tracking_code}";
    }

    public function getConversionRateAttribute()
    {
        if ($this->clicks_count == 0) return 0;
        return round(($this->conversions_count / $this->clicks_count) * 100, 1);
    }
}
