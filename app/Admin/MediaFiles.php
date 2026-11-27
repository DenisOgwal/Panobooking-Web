<?php

namespace App\Admin;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

/**
 * Photo files of a listing on the upload disk, named the way App\Support\Media serves them:
 * {folder}/{base}.jpg, {base}2.jpg … {base}N.jpg, with N kept in the listing's photo-count column.
 */
final class MediaFiles
{
    private static function disk()
    {
        return Media::disk();
    }

    public static function count(string $type, Model $record): int
    {
        $column = Media::countColumn($type);

        return $column ? max(0, (int) $record->getAttribute($column)) : (self::disk()->exists(Media::path($type, Media::baseName($type, $record), 1)) ? 1 : 0);
    }

    /** Store uploaded files as the next photos (or replace the single photo for types without a count). */
    public static function append(string $type, Model $record, array $uploads): void
    {
        $base = Media::baseName($type, $record);
        $column = Media::countColumn($type);
        $n = $column ? (int) $record->getAttribute($column) : 0;

        foreach ($uploads as $upload) {
            $n = $column ? $n + 1 : 1;
            $path = Media::path($type, $base, $n);
            $upload->storeAs(dirname($path), basename($path), config('panobooking.media_disk', 'media'));
        }

        if ($column) {
            $record->forceFill([$column => $n])->saveQuietly();
        }
    }

    /** Delete photo $n and shift the later ones down so the numbering stays contiguous. */
    public static function remove(string $type, Model $record, int $n): void
    {
        $base = Media::baseName($type, $record);
        $column = Media::countColumn($type);
        $count = $column ? (int) $record->getAttribute($column) : 1;
        if ($n < 1 || $n > max(1, $count)) {
            return;
        }

        self::disk()->delete(Media::path($type, $base, $n));
        for ($i = $n + 1; $i <= $count; $i++) {
            self::move(Media::path($type, $base, $i), Media::path($type, $base, $i - 1));
        }

        if ($column) {
            $record->forceFill([$column => max(0, $count - 1)])->saveQuietly();
        }
    }

    /** Make photo $n the main photo (swap with photo 1). */
    public static function makeMain(string $type, Model $record, int $n): void
    {
        if ($n <= 1) {
            return;
        }
        $base = Media::baseName($type, $record);
        $tmp = Media::path($type, $base . '__swap', 1);
        self::move(Media::path($type, $base, 1), $tmp);
        self::move(Media::path($type, $base, $n), Media::path($type, $base, 1));
        self::move($tmp, Media::path($type, $base, $n));
    }

    /** Rename every photo when the fields the file name is built from change. */
    public static function rename(string $type, string $fromBase, string $toBase, int $count): void
    {
        if ($fromBase === $toBase || $fromBase === '') {
            return;
        }
        for ($i = 1; $i <= max(1, $count); $i++) {
            self::move(Media::path($type, $fromBase, $i), Media::path($type, $toBase, $i));
        }
    }

    public static function deleteAll(string $type, string $base, int $count): void
    {
        for ($i = 1; $i <= max(1, $count); $i++) {
            self::disk()->delete(Media::path($type, $base, $i));
        }
    }

    private static function move(string $from, string $to): void
    {
        $disk = self::disk();
        if ($disk->exists($from)) {
            $disk->delete($to);
            $disk->move($from, $to);
        }
    }
}
