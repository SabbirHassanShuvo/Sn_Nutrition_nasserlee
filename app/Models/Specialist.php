<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'email',
        'phone',
        'avatar',
        'specialties',
        'bio',
        'available_slots',
        'is_active',
    ];

    protected $casts = [
        'specialties' => 'array',
        'available_slots' => 'array',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(ConsultationBooking::class);
    }
}
