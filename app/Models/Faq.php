<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded = ['id'];

    const STATUS = [
        'INACTIVE' => '0',
        'ACTIVE'   => '1',
        'PENDING'  => '2',
    ];

    public function faqCategory()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}
