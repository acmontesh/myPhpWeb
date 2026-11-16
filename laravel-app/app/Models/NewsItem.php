<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewsItem extends Model
{
    use HasTranslations;

    protected $table = 'news';

    public array $translatable = ['title', 'body'];

    protected $fillable = [
        'slug',
        'title',
        'body',
        'image1',
        'image2',
        'published_at',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'published_at' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }
}
