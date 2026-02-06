<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function uploadFile($file, $path)
    {
        return DB::transaction(function () use ($file, $path) {

            $filename = Str::uuid().'.'.$file->extension();

            // Store in a secure disk (e.g. local "public" or other)
            $path = $file->storeAs($path, $filename, 'public');

            // Optionally set visibility (if not default)
            Storage::disk('public')->setVisibility($path, 'public');

            return $path;
        });
    }
}
