<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            SectionSeeder::class,
            ThemeImageSlotSeeder::class,
            PublicPhraseSeeder::class,
            SocialLinkSeeder::class,
            AboutEntrySeeder::class,
            HeroSlideSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
