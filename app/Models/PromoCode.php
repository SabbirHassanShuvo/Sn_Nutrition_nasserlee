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

        if ($userId && $this->per_user_limit) {
            $userUsageCount = Order::where('user_id', $userId)
                ->where('applied_promo_code', $this->code)
                ->count();
            
            if ($userUsageCount >= $this->per_user_limit) {
                return false;
            }
        }

        return true;
    }
}
