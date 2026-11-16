<?php

namespace Database\Seeders;

use App\Models\AboutEntry;
use Illuminate\Database\Seeder;

/**
 * Seeds the About Me subsections with the real content already published on
 * the current site (includes/templates/about.php), so the new section ships
 * populated rather than empty. All of it is editable from the dashboard.
 */
class AboutEntrySeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            // Education
            ['education', 'fa-solid fa-graduation-cap', 'PhD Petroleum Engineering', 'The University of Texas at Austin — expected June 2026'],
            ['education', 'fa-solid fa-user-graduate', 'MS Systems and Computational Engineering', 'Pontificia Universidad Javeriana — Bogotá, Colombia, March 2022'],
            ['education', 'fa-solid fa-school', 'BEng Petroleum Engineering', 'Universidad Nacional de Colombia — Medellín, Colombia, September 2014'],

            // Professional experience
            ['experience', 'fa-solid fa-oil-well', 'Deep onshore wells in the Colombian foothills', null],
            ['experience', 'fa-solid fa-helmet-safety', 'Exploratory wells', null],
            ['experience', 'fa-solid fa-screwdriver-wrench', 'Thru-tubing ultra-slim laterals', null],

            // Research interests
            ['interests', 'fa-solid fa-chart-line', 'Drilling Anomaly Detection in Real Time', null],
            ['interests', 'fa-solid fa-helmet-safety', 'Stuck Pipe Prevention', null],
            ['interests', 'fa-solid fa-microscope', 'Wellbore Quality Assessment', null],
            ['interests', 'fa-solid fa-robot', 'Hole Cleaning Automation', null],
            ['interests', 'fa-solid fa-drafting-compass', 'Well Planning Automation', null],
            ['interests', 'fa-solid fa-square-root-variable', 'Analysis of Longitudinal Data', null],
        ];

        $orders = [];

        foreach ($entries as [$section, $icon, $title, $detail]) {
            $orders[$section] = ($orders[$section] ?? -1) + 1;

            AboutEntry::query()->firstOrCreate(
                ['section' => $section, 'title->en' => $title],
                [
                    'icon_class' => $icon,
                    'title' => ['en' => $title],
                    'detail' => $detail ? ['en' => $detail] : null,
                    'display_order' => $orders[$section],
                ]
            );
        }
    }
}
