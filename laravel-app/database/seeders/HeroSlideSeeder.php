<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Services\ImageUploadService;
use Illuminate\Database\Seeder;

/**
 * Imports the header carousel photography the client placed in
 * laravel-app/carrousel-header/. Sources are large originals (one is a 27 MB
 * PNG), so every file goes through ImageUploadService — resized to a 2000px
 * max width with a .webp sibling — rather than being served as-is.
 */
class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $sourceDir = base_path('carrousel-header');

        if (! is_dir($sourceDir)) {
            $this->command?->warn("carrousel-header folder not found at {$sourceDir} — skipping.");

            return;
        }

        $images = new ImageUploadService();
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'jfif'];
        $files = collect(scandir($sourceDir))
            ->reject(fn (string $file) => in_array($file, ['.', '..'], true))
            ->filter(fn (string $file) => in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $allowed, true))
            ->values();

        foreach ($files as $order => $file) {
            $caption = pathinfo($file, PATHINFO_FILENAME);

            if (HeroSlide::query()->where('caption', $caption)->exists()) {
                continue;
            }

            // jfif is just a jpeg; normalise the stored extension so browsers
            // and the webp sibling behave predictably.
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $extension = $extension === 'jfif' ? 'jpg' : $extension;

            $path = $images->storeFromPath($sourceDir.DIRECTORY_SEPARATOR.$file, 'uploads/hero', $extension);

            HeroSlide::query()->create([
                'image_path' => $path,
                'caption' => $caption,
                'display_order' => $order,
                'active' => true,
            ]);

            $this->command?->info("Imported header slide: {$file}");
        }
    }
}
