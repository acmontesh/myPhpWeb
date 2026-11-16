<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AboutContent extends Model
{
    use HasTranslations;

    protected $table = 'about_content';

    public array $translatable = ['headline', 'bio'];

    protected $fillable = ['headline', 'bio'];

    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }
}
