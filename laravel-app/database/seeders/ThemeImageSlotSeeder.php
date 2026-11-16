<?php

namespace Database\Seeders;

use App\Models\ThemeImageSlot;
use App\Services\ThemeManager;
use Illuminate\Database\Seeder;

class ThemeImageSlotSeeder extends Seeder
{
    private const DEFAULTS = [
        'hero_background' => 'theme-slots/hero-background.webp',
        'about_photo' => 'theme-slots/about-photo.webp',
    ];

    public function run(): void
    {
        foreach (array_keys(ThemeManager::THEMES) as $themeSlug) {
            foreach (ThemeImageSlot::SLOTS as $slotKey => $label) {
                ThemeImageSlot::query()->firstOrCreate(
                    ['theme_slug' => $themeSlug, 'slot_key' => $slotKey],
                    ['label' => $label, 'default_image_path' => self::DEFAULTS[$slotKey]]
                );
            }
        }
    }
}
