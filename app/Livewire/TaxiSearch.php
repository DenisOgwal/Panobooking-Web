<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleResults;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

/** /TaxiSearch?pickuppoint=From Airport&airport=Entebbe&Destination=Kampala */
class TaxiSearch extends VehicleResults
{
    #[Url(as: 'Destination')]
    public $url_Destination = '';

    #[Url(as: 'pickuppoint')]
    public $url_pickuppoint = '';

    #[Url(as: 'airport')]
    public $url_airport = '';

    protected function type(): string { return 'taxi'; }

    protected function filter(Builder $query): Builder
    {
        return $query->forDestination($this->url_Destination);
    }

    protected function heading(): array
    {
        $dest = trim((string) $this->url_Destination);
        $airport = trim((string) $this->url_airport) ?: 'Entebbe';
        $toAirport = $this->url_pickuppoint === 'To Airport';

        $title = $dest === '' ? 'Find an airport taxi'
            : ($toAirport ? "{$dest} to {$airport} Airport" : "{$airport} Airport to {$dest}");

        return [$title, 'Choose a car and book a fixed-price transfer.', 'Search'];
    }
}
