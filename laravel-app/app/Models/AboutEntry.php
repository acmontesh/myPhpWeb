<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AboutEntry extends Model
{
    use HasTranslations;

    // The three About Me subsections. Keys map to lang/{locale}/site.php so the
    // public headings translate; the admin UI uses the English labels here.
    public const SECTIONS = [
        'education' => 'Education',
        'experience' => 'Professional Experience',
        'interests' => 'My Research Interests',
    ];

    // Curated FontAwesome icons offered in the admin picker — chosen to cover
    // academic, engineering and research bullet points.
    public const ICONS = [
        'fa-solid fa-graduation-cap',
        'fa-solid fa-user-graduate',
        'fa-solid fa-school',
        'fa-solid fa-book-open',
        'fa-solid fa-scroll',
        'fa-solid fa-award',
        'fa-solid fa-trophy',
        'fa-solid fa-medal',
        'fa-solid fa-briefcase',
        'fa-solid fa-building-columns',
        'fa-solid fa-industry',
        'fa-solid fa-helmet-safety',
        'fa-solid fa-oil-well',
        'fa-solid fa-gears',
        'fa-solid fa-screwdriver-wrench',
        'fa-solid fa-drafting-compass',
        'fa-solid fa-flask',
        'fa-solid fa-microscope',
        'fa-solid fa-atom',
        'fa-solid fa-brain',
        'fa-solid fa-robot',
        'fa-solid fa-microchip',
        'fa-solid fa-network-wired',
        'fa-solid fa-code',
        'fa-solid fa-database',
        'fa-solid fa-chart-line',
        'fa-solid fa-square-root-variable',
        'fa-solid fa-lightbulb',
        'fa-solid fa-rocket',
        'fa-solid fa-globe',
        'fa-solid fa-people-group',
        'fa-solid fa-circle-check',
    ];

    public array $translatable = ['title', 'detail'];

    protected $fillable = ['section', 'icon_class', 'title', 'detail', 'display_order'];

    public function scopeSection(Builder $query, string $section): Builder
    {
        return $query->where('section', $section);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderBy('id');
    }

    public static function sectionLabel(string $section): string
    {
        return self::SECTIONS[$section] ?? $section;
    }
}
