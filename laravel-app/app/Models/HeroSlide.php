<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Background images for the homepage header carousel. When none are active the
 * hero falls back to the single `hero_background` theme image slot (Phase 9),
 * so this is additive rather than a replacement.
 */
class HeroSlide extends Model
{
    protected $fillable = ['image_path', 'caption', 'display_order', 'active'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    /**
     * Prefer the .webp sibling generated on upload — these are full-bleed
     * background photos, so the saving is significant (the largest original
     * is 3.7 MB against a 0.4 MB webp). Falls back to the original if the
     * sibling is missing for any reason.
     */
    public function displayPath(): string
    {
        $webp = preg_replace('/\.[^.]+$/', '.webp', $this->image_path);

        return Storage::disk('public')->exists($webp) ? $webp : $this->image_path;
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
