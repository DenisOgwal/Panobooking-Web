<?php

namespace App\Support;

use App\Models\BlogModel;
use App\Models\DestinationsModel;
use App\Models\events as EventModel;
use App\Models\FeedBackModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\RoomsModel;
use App\Models\TourismSite;
use Illuminate\Support\Facades\Cache;

/** Home page content (website and GET /api/v1/home), cached for five minutes. */
final class HomeContent
{
    public const CACHE_KEY = 'home.content';

    public static function get(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            $hotelCounts = HotelsModel::approved()->selectRaw('City, COUNT(*) as total')->groupBy('City')->pluck('total', 'City');

            return [
                'offer' => RoomsModel::offered()->with('hotel:IDs,Place,City')->orderByDesc('IDs')->first(),
                'trending' => HotelsModel::approved()->trending()->orderBy('TrendRank')->limit(12)->get(),
                'featured' => HotelsModel::approved()->featured()->orderBy('FaturedRank')->limit(12)->get(),
                'destinations' => DestinationsModel::orderBy('DestinationID')->limit(8)->get()
                    ->each(fn ($d) => $d->setAttribute('stays', (int) ($hotelCounts[$d->City] ?? 0))),
                'sites' => TourismSite::orderBy('ID')->limit(8)->get(),
                'events' => EventModel::upcoming()->orderBy('StartDate')->limit(6)->get(),
                'articles' => BlogModel::withCount('approvedComments')->orderByDesc('id')->limit(3)->get(),
                'testimonials' => FeedBackModel::approved()->orderByDesc('id')->limit(6)->get(),
                'stats' => [
                    'stays' => HotelsModel::approved()->count(),
                    'cars' => RentalCarModel::approved()->count(),
                    'cities' => $hotelCounts->count(),
                ],
            ];
        });
    }
}
