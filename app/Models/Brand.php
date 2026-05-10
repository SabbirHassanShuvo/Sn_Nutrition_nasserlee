<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
