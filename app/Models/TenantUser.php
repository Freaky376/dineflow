<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TenantUser extends Authenticatable
{
    protected $table = 'users'; // Ensure this model uses the 'users' table

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
