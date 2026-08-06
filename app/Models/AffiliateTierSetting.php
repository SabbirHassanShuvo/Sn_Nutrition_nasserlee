<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateTierSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier_key',
        'tier_name',
        'min_earnings',
        'bonus_percent',
        'badge_color',
    ];

    protected $casts = [
        'min_earnings' => 'float',
        'bonus_percent' => 'float',
    ];
}
