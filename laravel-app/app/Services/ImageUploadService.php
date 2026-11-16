<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * Every image field in the dashboard goes through here (implementation-plan.md
 * §3.5): resize down to a sane max width, save the original, and always
 * generate a .webp sibling — same {name}.{ext} + {name}.webp convention the
 * old site already used under Imagenes/.
 */
class ImageUploadService
{
    private const MAX_WIDTH = 2000;

    public function store(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $relativePath = trim($directory, '/').'/'.$filename;

        $manager = ImageManager::gd();
        $image = $manager->read($file->getRealPath());
        $image->scaleDown(width: self::MAX_WIDTH);

        Storage::disk('public')->makeDirectory($directory);
        $image->save(Storage::disk('public')->path($relativePath));
        $image->toWebp(quality: 82)->save(Storage::disk('public')->path($this->webpPath($relativePath)));

        return $relativePath;
    }

    /**
     * Same pipeline as store(), but for a file already on disk rather than an
     * upload — used to import the project's existing imagery (e.g. the header
     * carousel photos) through the identical resize + webp path.
     */
    public function storeFromPath(string $sourcePath, string $directory, ?string $extension = null): string
    {
        $extension = $extension ?: (pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg');
        $filename = Str::uuid().'.'.$extension;
        $relativePath = trim($directory, '/').'/'.$filename;

        $manager = ImageManager::gd();
        $image = $manager->read($sourcePath);
        $image->scaleDown(width: self::MAX_WIDTH);

        Storage::disk('public')->makeDirectory($directory);
        $image->save(Storage::disk('public')->path($relativePath));
        $image->toWebp(quality: 82)->save(Storage::disk('public')->path($this->webpPath($relativePath)));

        return $relativePath;
    }

    public function delete(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        Storage::disk('public')->delete([$relativePath, $this->webpPath($relativePath)]);
    }

    public function webpPath(string $relativePath): string
    {
        return preg_replace('/\.[^.]+$/', '.webp', $relativePath);
    }
}
