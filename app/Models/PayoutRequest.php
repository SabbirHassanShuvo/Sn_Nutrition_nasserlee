<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_number',
        'user_id',
        'type',
        'amount',
        'bank_name',
        'account_name',
        'account_number',
        'routing_number',
        'card_last_four',
        'card_type',
        'status',
        'receipt_image',
        'admin_notes',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
    ];

    protected $appends = [
        'receipt_url',
        'method_display',
    ];

    public function getReceiptUrlAttribute()
    {
        if ($this->receipt_image) {
            return asset($this->receipt_image);
        }
        return null;
    }

    public function getMethodDisplayAttribute()
    {
        if ($this->type === 'debit_card') {
            return ($this->card_type ?: 'Debit Card') . ' **' . ($this->card_last_four ?: '2917');
        }
        return ($this->bank_name ?: 'Bank Transfer') . ' **' . substr($this->account_number, -4);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
