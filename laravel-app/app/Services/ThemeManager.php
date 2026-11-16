<?php

namespace App\Services;

use App\Models\Setting;

/**
 * Resolves the active public-site theme (implementation-plan.md §3.3) and
 * exposes what controllers/views need to render it.
 *
 * Content section views (resources/views/themes/base/*) are shared across
 * every theme — only the layout (header/footer/typography/shape language)
 * differs per theme, via resources/views/themes/{slug}/layouts/app.blade.php
 * and resources/scss/themes/{slug}/app.scss. This keeps the 5 themes from
 * requiring 5x duplicated content templates while still giving each one a
 * genuinely distinct look, per the "component reutilization" requirement.
 */
class ThemeManager
{
    public const DEFAULT_THEME = 'aurora';

    public const THEMES = [
        'aurora' => [
            'label' => 'Aurora',
            'description' => 'Warm and academic — burnt orange on white, generous whitespace. The site\'s default look.',
            'swatches' => ['#ffffff', '#cc5500', '#1a1a1a'],
            'mode' => 'light',
        ],
        'slate' => [
            'label' => 'Slate',
            'description' => 'Technical and precise — charcoal with an electric blue accent and monospace touches.',
            'swatches' => ['#12151c', '#2f6fed', '#e7eaf0'],
            'mode' => 'dark',
        ],
        'ivory' => [
            'label' => 'Ivory',
            'description' => 'Editorial and minimal — serif headings on cream, with a deep forest-green accent.',
            'swatches' => ['#faf7f0', '#1f4d3a', '#2b2620'],
            'mode' => 'light',
        ],
        'nova' => [
            'label' => 'Nova',
            'description' => 'Bold and creative — a vibrant magenta accent, rounded shapes, and large type.',
            'swatches' => ['#ffffff', '#d6247a', '#20122b'],
            'mode' => 'light',
        ],
        'graphite' => [
            'label' => 'Graphite',
            'description' => 'Dark and technical — near-black with a neon-lime accent and terminal-inspired headings.',
            'swatches' => ['#0b0d10', '#8ef05a', '#c9d1d9'],
            'mode' => 'dark',
        ],
    ];

    public function active(): string
    {
        $theme = Setting::get('active_theme', self::DEFAULT_THEME);

        return $this->exists($theme) ? $theme : self::DEFAULT_THEME;
    }

    public function layout(?string $theme = null): string
    {
        $theme = $theme && $this->exists($theme) ? $theme : $this->active();

        return "themes.{$theme}.layouts.app";
    }

    public function view(string $section): string
    {
        return "themes.base.{$section}";
    }

    public function all(): array
    {
        return self::THEMES;
    }

    public function exists(string $theme): bool
    {
        return array_key_exists($theme, self::THEMES);
    }

    public function isActive(string $theme): bool
    {
        return $this->active() === $theme;
    }

    public function isDark(): bool
    {
        return (self::THEMES[$this->active()]['mode'] ?? 'light') === 'dark';
    }
}
