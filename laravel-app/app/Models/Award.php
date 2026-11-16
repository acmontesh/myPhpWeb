<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Award extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'description'];

    protected $fillable = [
        'title',
        'granted_by',
        'awarded_on',
        'description',
        'image',
        'display_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'awarded_on' => 'date',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Manual ordering first, then most recent award — so the engineer can pin
     * a highlight to the top without losing the chronological default.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderByDesc('awarded_on')->orderByDesc('id');
    }
}
