<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // 8 dashboard accent color presets (Phase 10) — one is blue, the default.
    public const ACCENT_COLORS = [
        '#2F6FED' => 'Blue',
        '#CC5500' => 'Burnt Orange',
        '#1F8A4C' => 'Emerald',
        '#7C3AED' => 'Violet',
        '#DB2777' => 'Rose',
        '#0D9488' => 'Teal',
        '#D97706' => 'Amber',
        '#475569' => 'Slate',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar_path',
        'ui_color_mode',
        'ui_accent_color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
