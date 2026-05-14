<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PayoutHistory extends Model
{
    protected $table = 'payout_history';
    
    protected $fillable = [
        'user_id',
        'amount',
        'payout_method',
        'status',
        'payout_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
