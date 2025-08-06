<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'college_id', 'status', 'role'
    ];

    protected $casts = [
        'status' => 'string',
        'role' => 'string',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}