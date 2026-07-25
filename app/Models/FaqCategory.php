<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $guarded = ['id'];

    const STATUS = [
        'INACTIVE' => '0',
        'ACTIVE'   => '1',
    ];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'faq_category_id');
    }

    public function activeFaqs()
    {
        return $this->hasMany(Faq::class, 'faq_category_id')
                    ->where('status', Faq::STATUS['ACTIVE'])
                    ->orderBy('priority');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS['ACTIVE']);
    }
}
