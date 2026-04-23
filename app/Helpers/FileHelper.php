<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Delete a file from a specific disk if it exists.
     * 
     * @param string|null $path The relative path to the file.
     * @param string $disk The disk to delete from (default: public).
     * @return bool True if deleted or if path was null/invalid, False otherwise.
     */
    public static function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return true;
        }

        // Avoid deleting external URLs or data URIs
        if (Str::startsWith($path, ['http://', 'https://', 'data:', '/'])) {
            return true;
        }

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return true;
    }
}
