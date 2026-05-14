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
    ];

    protected $casts = [
        'specialties' => 'array',
        'certifications' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
