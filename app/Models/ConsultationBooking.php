<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'specialist_id',
        'call_type',
        'user_name',
        'user_email',
        'user_phone',
        'notes',
        'booking_date',
        'booking_time',
        'status',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'zoom_password',
        'approved_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
