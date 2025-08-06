<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'batch',
        'course',
        'skills',
        'socials',
        'bio',
        'current_status',
        'location',
        'phone',
        'photo',
    ];

    protected $casts = [
        'skills' => 'array',
        'socials' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}