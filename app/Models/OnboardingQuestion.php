<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'serial_number',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function answers()
    {
        return $this->hasMany(OnboardingAnswer::class, 'onboarding_question_id');
    }
}
