<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'ip_address',
        'user_agent',
        'status',
        'captcha_verified',
    ];

    protected function casts(): array
    {
        return [
            'captcha_verified' => 'boolean',
        ];
    }
}
