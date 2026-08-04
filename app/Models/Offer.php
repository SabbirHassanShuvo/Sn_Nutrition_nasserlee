<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'badge',
        'promo_code',
        'bg_color',
        'banner_image',
        'discount_percent',
        'expire_date',
        'position',
        'status',
    ];

    protected $casts = [
        'discount_percent' => 'float',
        'expire_date' => 'datetime',
        'status' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>', now());
            });
    }
}
