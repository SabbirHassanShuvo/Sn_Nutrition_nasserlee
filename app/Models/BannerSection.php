<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerSection extends Model
{
    protected $guarded = ['id'];

    const STATUS = [
        'INACTIVE' => 0,
        'ACTIVE' => 1,
    ];
}
