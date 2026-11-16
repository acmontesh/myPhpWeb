<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_mode' => 'multipage',
            'active_theme' => 'aurora',
            'default_locale' => 'en',
            'hero_overlay_alpha' => (string) Setting::HERO_OVERLAY_ALPHA_DEFAULT,
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
