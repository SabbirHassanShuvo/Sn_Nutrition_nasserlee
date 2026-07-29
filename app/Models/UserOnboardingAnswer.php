<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOnboardingAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'onboarding_question_id',
        'onboarding_answer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(OnboardingQuestion::class, 'onboarding_question_id');
    }

    public function answer()
    {
        return $this->belongsTo(OnboardingAnswer::class, 'onboarding_answer_id');
    }
}
