<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeImageSlot extends Model
{
    // Fixed set of slots every theme ships with (implementation-plan.md §5/§7
    // Phase 9) — not admin-creatable, just admin-editable per theme.
    public const SLOTS = [
        'hero_background' => 'Homepage Hero Background',
        'about_photo' => 'About Me Photo',
    ];

    protected $fillable = [
        'theme_slug',
        'slot_key',
        'label',
        'current_image_path',
        'default_image_path',
    ];

    public function resolvedPath(): ?string
    {
        return $this->current_image_path ?? $this->default_image_path;
    }

    public static function resolve(string $themeSlug, string $slotKey): ?string
    {
        return static::query()
            ->where('theme_slug', $themeSlug)
            ->where('slot_key', $slotKey)
            ->first()
            ?->resolvedPath();
    }
}
