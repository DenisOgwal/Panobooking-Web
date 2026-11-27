<?php

namespace App\Http\Controllers;

use App\Support\Media;

/** GET /media/{path}: a photo uploaded on this site (see App\Support\Media). */
class MediaController extends Controller
{
    private const TYPES = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];

    public function __invoke(string $path)
    {
        $path = ltrim($path, '/');
        $top = strtok($path, '/');
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        abort_if(str_contains($path, '..') || str_contains($path, "\0") || ! in_array($top, Media::FOLDERS, true), 404);
        abort_unless(isset(self::TYPES[$ext]), 404);

        $disk = Media::disk();
        abort_unless($disk->exists($path), 404);

        // Uploads are stored as .jpg whatever their real format; let the browser sniff PNG/WebP.
        $mime = @mime_content_type($disk->path($path)) ?: self::TYPES[$ext];

        return response()->file($disk->path($path), [
            'Content-Type' => str_starts_with($mime, 'image/') ? $mime : self::TYPES[$ext],
            'Cache-Control' => 'public, max-age=604800',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
