<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function uploadImage($file, $path)
    {
        $filename = Str::uuid() . '.' . $file->extension();

        // Store in a secure disk (e.g. local "public" or other)
        $path = $file->storeAs($path, $filename, 'public');

        // Optionally set visibility (if not default)
        Storage::disk('public')->setVisibility($path, 'public');

        return $path;
    }
}
