<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'bank_name',
        'account_name',
        'account_number',
        'routing_number',
        'card_last_four',
        'card_type',
        'expiry_date',
        'fees_info',
        'delivery_time',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
