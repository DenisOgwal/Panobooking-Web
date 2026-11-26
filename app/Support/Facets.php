<?php

namespace App\Support;

use App\Models\HomeGuestModel;
use App\Models\HotelsModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/** Cached counts used for filter sidebars. */
class Facets
{
    private const TTL = 300;

    /** @return Collection<string, int> City => number of approved hotels */
    public static function hotelCities(): Collection
    {
        return Cache::remember('facets.hotel-cities', self::TTL, fn () => HotelsModel::approved()
            ->whereNotNull('City')->selectRaw('City, COUNT(*) as total')->groupBy('City')->orderBy('City')
            ->pluck('total', 'City')->map(fn ($n) => (int) $n));
    }

    /** @return Collection<string, int> City => number of guest houses */
    public static function guestHouseCities(): Collection
    {
        return Cache::remember('facets.guesthouse-cities', self::TTL, fn () => HomeGuestModel::query()
            ->whereNotNull('City')->selectRaw('City, COUNT(*) as total')->groupBy('City')->orderBy('City')
            ->pluck('total', 'City')->map(fn ($n) => (int) $n));
    }
}
