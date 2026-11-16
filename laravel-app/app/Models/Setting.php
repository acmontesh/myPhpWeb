<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function siteName(): string
    {
        return static::get('site_name') ?: config('app.name');
    }

    public static function siteTagline(): ?string
    {
        if (app()->getLocale() === 'es' && ($spanish = static::get('site_tagline_es'))) {
            return $spanish;
        }

        return static::get('site_tagline');
    }

    // Public-site motion & effects (Appearance -> Motion & Effects).
    public const MOTION_DEFAULTS = [
        'motion_enabled' => '1',
        'motion_cursor' => '1',
        'motion_parallax' => 'subtle',
        'motion_intro' => '1',
        'home_marquee' => '1',
        'home_stats' => '1',
    ];

    public const PARALLAX_LEVELS = ['off' => 0, 'subtle' => 1, 'bold' => 1.8];

    public static function motion(): array
    {
        $stored = static::query()->whereIn('key', array_keys(self::MOTION_DEFAULTS))->pluck('value', 'key')->all();

        return array_merge(self::MOTION_DEFAULTS, array_filter($stored, fn ($value) => $value !== null));
    }

    public static function motionFlag(string $key): bool
    {
        return (static::motion()[$key] ?? '0') === '1';
    }

    // Opacity of the accent-coloured wash over the homepage header carousel,
    // stored as a whole percentage and returned as a 0-1 CSS opacity.
    public const HERO_OVERLAY_ALPHA_DEFAULT = 86;

    public static function heroOverlayAlpha(): float
    {
        $percent = (int) static::get('hero_overlay_alpha', (string) self::HERO_OVERLAY_ALPHA_DEFAULT);

        return max(0, min(100, $percent)) / 100;
    }
}
