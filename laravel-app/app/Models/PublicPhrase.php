<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PublicPhrase extends Model
{
    use HasTranslations;

    public array $translatable = ['value'];

    protected $fillable = ['key', 'group', 'value'];

    public static function text(string $key, ?string $fallback = null): ?string
    {
        $phrase = static::query()->where('key', $key)->first();

        if (! $phrase) {
            return $fallback;
        }

        return $phrase->getTranslation('value', app()->getLocale(), false) ?: $fallback;
    }
}
