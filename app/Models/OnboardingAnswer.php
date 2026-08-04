<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'onboarding_question_id',
        'answer_text',
    ];

    public function question()
    {
        return $this->belongsTo(OnboardingQuestion::class, 'onboarding_question_id');
    }
}
