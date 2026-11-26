<?php

namespace App\Livewire\Concerns;

use App\Models\AmbulanceModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;

/**
 * Stays, cars, taxis and ambulances relevant to a tourism site or event: in the same city,
 * or whose search terms mention the site/event name or keywords.
 */
trait FindsNearbyListings
{
    protected function nearbyListings(string $city, array $terms): array
    {
        $terms = array_values(array_filter(array_map('trim', [...$terms, $city])));
        $near = fn ($q) => $q->where(fn ($w) => $w->where('City', $city)->orWhere(fn ($o) => $o->searchAny($terms, ['SearchTerms'])));

        return [
            'hotels' => HotelsModel::approved()->where($near)->orderByRaw('TrendRank IS NULL')->orderBy('TrendRank')->limit(12)->get(),
            'cars' => RentalCarModel::approved()->where($near)->limit(8)->get(),
            'taxis' => TaxiModel::forDestination($city, $terms)->limit(8)->get(),
            'ambulances' => AmbulanceModel::approved()->where($near)->limit(8)->get(),
        ];
    }
}
