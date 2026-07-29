<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_text',
        'title',
        'description',
        'footnote_text',
    ];
}
