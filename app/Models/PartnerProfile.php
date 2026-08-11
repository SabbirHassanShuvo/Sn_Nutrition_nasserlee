<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'professional_role',
        'years_of_experience',
        'bio',
        'location',
        'phone',
        'website',
        'specialties',
        'certifications',
        'onboarding_step',
        'lifetime_earnings',
        'pending_payout',
        'last_paid_amount',
        'current_tier',
        'avatar',
    ];

    protected $casts = [
        'specialties' => 'array',
        'certifications' => 'array',
    ];

    protected $appends = ['step_one', 'step_two', 'step_three', 'step_four'];

    protected $hidden = [
        'professional_role',
        'years_of_experience',
        'bio',
        'location',
        'phone',
        'website',
        'specialties',
        'certifications',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    public function getStepOneAttribute()
    {
        return [
            'professional_role' => $this->professional_role,
            'years_of_experience' => $this->years_of_experience,
            'bio' => $this->bio,
        ];
    }

    public function getStepTwoAttribute()
    {
        return [
            'location' => $this->location,
            'phone' => $this->phone,
            'website' => $this->website,
        ];
    }

    public function getStepThreeAttribute()
    {
        return [
            'specialties' => $this->specialties ?? [],
        ];
    }

    public function getStepFourAttribute()
    {
        return [
            'certifications' => $this->certifications ?? [],
        ];
    }
}
