<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'category_id',
        'product_id',
        'brand_id',
        'health_professional_id',
        'discount_percent',
        'expiry_date',
        'usage_limit',
        'per_user_limit',
        'used_count',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Check if the promo code is valid.
     */
    public function isValid($userId = null): bool
    {
        if (!$this->status) {
            return false;
        }

        if ($this->expiry_date && now()->isAfter($this->expiry_date)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($userId) {
            // Check per user limit
            if ($this->per_user_limit) {
                $userUsageCount = Order::where('user_id', $userId)
                    ->where('applied_promo_code', $this->code)
                    ->count();
                
                if ($userUsageCount >= $this->per_user_limit) {
                    return false;
                }
            }

            // If health_professional type, ensure the user using it ISN'T the professional themselves? 
            // Or maybe it's only for specific users? 
            // Usually, these are referral codes. 
            // If we want to RESTRICT by professional, we'd need more logic. 
            // For now, let's assume if it's health_professional type, it's just a tag for tracking.
        }

        return true;
    }

    /**
     * Calculate discount amount for a set of items.
     */
    public function calculateDiscount($items)
    {
        $discountTotal = 0;

        foreach ($items as $item) {
            // Handle both array and object
            $product = is_array($item) ? ($item['product'] ?? null) : ($item->product ?? null);
            $price = is_array($item) ? ($item['price'] ?? ($product->price ?? 0)) : ($item->price ?? ($product->price ?? 0));
            $quantity = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);

            if (!$product) continue;

            $isApplicable = false;

            switch ($this->type) {
                case 'global':
                    $isApplicable = true;
                    break;
                case 'category':
                    $isApplicable = $product->category_id == $this->category_id;
                    break;
                case 'product':
                    $isApplicable = $product->id == $this->product_id;
                    break;
                case 'brand':
                    $isApplicable = $product->brand_id == $this->brand_id;
                    break;
                case 'health_professional':
                    $isApplicable = true;
                    break;
            }

            if ($isApplicable) {
                $itemTotal = $price * $quantity;
                $discountTotal += ($itemTotal * $this->discount_percent) / 100;
            }
        }

        return $discountTotal;
    }

    /**
     * Get applicability error message.
     */
    public function getApplicabilityMessage($items)
    {
        if ($items->isEmpty()) {
            return 'Your cart is empty.';
        }

        $discount = $this->calculateDiscount($items);
        if ($discount > 0) {
            return null; // Applicable
        }

        return 'This promo code is not applicable to the items in your cart.';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function healthProfessional()
    {
        return $this->belongsTo(User::class, 'health_professional_id');
    }
}
