<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

// Seeded from the profiles already linked on the old site (index.php).
class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            ['platform' => 'Google Scholar', 'url' => 'https://scholar.google.com/citations?user=Va0vQdsAAAAJ&hl=es', 'icon_class' => 'fa-solid fa-graduation-cap', 'display_order' => 0],
            ['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/abraham-c-montes-6661a841/', 'icon_class' => 'fa-brands fa-linkedin-in', 'display_order' => 1],
            ['platform' => 'ResearchGate', 'url' => 'https://www.researchgate.net/profile/Abraham-Montes-4', 'icon_class' => 'fa-brands fa-researchgate', 'display_order' => 2],
            ['platform' => 'GitHub', 'url' => 'https://github.com/acmontesh', 'icon_class' => 'fa-brands fa-github', 'display_order' => 3],
        ];

        foreach ($links as $link) {
            SocialLink::query()->firstOrCreate(['platform' => $link['platform']], $link);
        }
    }
}
