<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['key' => 'about', 'label' => 'About Me'],
            ['key' => 'news', 'label' => 'News'],
            ['key' => 'research', 'label' => 'Research'],
            ['key' => 'publications', 'label' => 'Publications'],
            ['key' => 'blog', 'label' => 'Blog'],
            ['key' => 'quotes', 'label' => 'Quotes I Like'],
            ['key' => 'contact', 'label' => 'Contact Me'],
        ];

        foreach ($sections as $order => $section) {
            Section::query()->firstOrCreate(
                ['key' => $section['key']],
                ['label' => $section['label'], 'enabled' => true, 'display_order' => $order]
            );
        }

        // Added after the original seven (Revision 5). Given the same order as
        // Publications so it slots in directly after it without renumbering
        // rows the engineer may already have reordered by hand — ties break by
        // id, and it stays reorderable from the Sections screen.
        Section::query()->firstOrCreate(
            ['key' => 'awards'],
            [
                'label' => 'Awards & Distinctions',
                'enabled' => true,
                'display_order' => Section::query()->where('key', 'publications')->value('display_order') ?? 3,
            ]
        );
    }
}
