<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * Process an uploaded image, crop it to a square, resize, encode to WebP, and save.
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $directory The directory in storage/app/public to save the image
     * @param int $size The target width/height (square)
     * @param int $quality The WebP compression quality
     * @return string The relative path to the saved image
     */
    public static function processAndSaveAvatar(UploadedFile $file, string $directory, int $size = 500, int $quality = 80): string
    {
        // Init Intervention Image Manager with GD driver
        $manager = new ImageManager(new Driver());

        // Read the image
        $image = $manager->read($file->getRealPath());

        // Resize and crop to fill the given dimensions
        $image->cover($size, $size);

        // Encode to WebP format
        $encoded = $image->toWebp($quality);

        // Generate a unique filename
        $filename = \Illuminate\Support\Str::random(40) . '.webp';
        $path = trim($directory, '/') . '/' . $filename;

        // Save to public disk
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}
