<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Builds photo URLs for listings.
 *
 * Photos live on the media host (config('panobooking.media_url')) and are named after the
 * listing, e.g. AndroidFiles/DestinationImages/{Place}.jpg. A gallery of N photos is
 * {name}.jpg, {name}2.jpg … {name}N.jpg, where N is the listing's PhotoUrl / PhotoUri count.
 *
 * Photos uploaded on this site (admin, listing forms, API) are stored on the media disk under the
 * same paths; when a photo exists there it is served by the site (/media/…), otherwise it is
 * loaded from the media host.
 */
class Media
{
    public const PLACEHOLDER = 'images/placeholder-listing.svg';

    /** type => [folder, callable(Model): string base name, photo-count column] */
    private static function types(): array
    {
        return [
            'hotel'      => ['AndroidFiles/DestinationImages', fn ($m) => $m->Place, 'PhotoUrl'],
            'guesthouse' => ['AndroidFiles/GuestHouseImages', fn ($m) => $m->GuestHouseName, 'PhotoUrl'],
            'room'       => ['AndroidFiles/RoomImages', fn ($m) => $m->HotelName . $m->RoomName, 'PhotoUrl'],
            'car'        => ['AndroidDriver/FileUpload', fn ($m) => $m->DriverName . $m->TaxiNo, 'PhotoUri'],
            'taxi'       => ['AndroidDriver/FileUpload', fn ($m) => $m->TaxiNo, 'PhotoUri'],
            'ambulance'  => ['AndroidDriver/FileUpload', fn ($m) => $m->TaxiNo, 'PhotoUri'],
            'blog'       => ['AndroidFiles/BlogsThumbnail', fn ($m) => $m->BlogTitle, null],
            'site'       => ['AndroidFiles/TourismSiteThumbnail', fn ($m) => $m->SiteName, 'PhotoUri'],
            'event'      => ['AndroidFiles/EventsThumbnail', fn ($m) => $m->EventName, 'PhotoUri'],
            'tour'       => ['AndroidFiles/TourPackages', fn ($m) => $m->TravelCompany . $m->PackageName, 'PhotoUri'],
            'city'       => ['AndroidFiles/CityImages', fn ($m) => $m->City, null],
        ];
    }

    /** Top-level folders photos may live in (also what /media/… is allowed to serve). */
    public const FOLDERS = ['AndroidFiles', 'AndroidDriver'];

    public static function disk()
    {
        return Storage::disk(config('panobooking.media_disk', 'media'));
    }

    /** Folder a listing type's photos live in, e.g. AndroidFiles/DestinationImages. */
    public static function folder(string $type): string
    {
        return self::types()[$type][0];
    }

    /** File base name of a listing's photos ({base}.jpg, {base}2.jpg …). */
    public static function baseName(string $type, Model $model): string
    {
        return (string) (self::types()[$type][1])($model);
    }

    /** Column holding the photo count, or null when the type has a single photo. */
    public static function countColumn(string $type): ?string
    {
        return self::types()[$type][2];
    }

    /** Relative path of photo number $n (1-based) of a listing, as stored on disk and on the media host. */
    public static function path(string $type, string $base, int $n): string
    {
        return self::folder($type) . '/' . $base . ($n === 1 ? '' : $n) . '.jpg';
    }

    /** Main photo of a listing. */
    public static function image(string $type, Model $model): string
    {
        return self::gallery($type, $model, 1)[0];
    }

    /**
     * All photos of a listing (at least one URL, at most $max).
     *
     * @return list<string>
     */
    public static function gallery(string $type, Model $model, ?int $max = null): array
    {
        [$folder, $name, $countColumn] = self::types()[$type];
        $base = (string) $name($model);

        $count = $countColumn ? max(1, (int) $model->{$countColumn}) : 1;
        if ($max !== null) {
            $count = min($count, $max);
        }

        $urls = [];
        for ($i = 1; $i <= $count; $i++) {
            $urls[] = self::url($folder, $base . ($i === 1 ? '' : $i) . '.jpg');
        }

        return $urls;
    }

    /** Category tile photo (car / ambulance categories). */
    public static function category(string $category): string
    {
        return self::url('AndroidDriver/FileUpload/CarCategory', $category . '.jpg');
    }

    public static function city(string $city): string
    {
        return self::url('AndroidFiles/CityImages', $city . '.jpg');
    }

    public static function placeholder(): string
    {
        return asset(self::PLACEHOLDER);
    }

    private static function url(string $folder, string $file): string
    {
        $path = $folder . '/' . $file;
        $disk = self::disk();

        if ($disk->exists($path)) {
            // Cache-busting version, so a replaced photo shows up straight away.
            return url('/media/' . implode('/', array_map('rawurlencode', explode('/', $path)))) . '?v=' . $disk->lastModified($path);
        }

        return config('panobooking.media_url') . '/' . $folder . '/' . rawurlencode($file);
    }
}
