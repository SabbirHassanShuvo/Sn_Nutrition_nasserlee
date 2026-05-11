<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percent',
        'expiry_date',
        'usage_limit',
        'used_count',
        'status'
    ];

    /**
     * Check if the promo code is valid.
     */
    public function isValid(): bool
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

        return true;
    }
}
