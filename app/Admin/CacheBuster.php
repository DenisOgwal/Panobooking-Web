<?php

namespace App\Admin;

use Illuminate\Support\Facades\Cache;

/** Forgets the public site's cached listings after an admin change, so edits show up at once. */
final class CacheBuster
{
    private const ALWAYS = ['home.content', 'footer.properties', 'footer.articles', 'facets.hotel-cities', 'facets.guesthouse-cities',
        'vehicles.car.categories', 'vehicles.taxi.categories', 'vehicles.ambulance.categories'];

    public static function flush(array $extra = []): void
    {
        foreach ([...self::ALWAYS, ...$extra] as $key) {
            Cache::forget($key);
        }
    }
}
