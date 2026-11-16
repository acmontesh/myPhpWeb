<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Publication extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'abstract'];

    protected $fillable = [
        'doi',
        'type',
        'title',
        'abstract',
        'image',
        'pdf_path',
        'video_url',
        'citation_count',
        'citation_source',
        'citation_synced_at',
        'display_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'citation_synced_at' => 'datetime',
        ];
    }

    public function doiUrl(): string
    {
        return 'https://doi.org/'.$this->doi;
    }

    public function hasPdf(): bool
    {
        return ! empty($this->pdf_path);
    }

    public function hasVideo(): bool
    {
        return ! empty($this->video_url);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderBy('id');
    }
}
