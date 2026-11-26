<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleResults;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

/** /AmbulanceSearch?Area=... */
class AmbulanceSearch extends VehicleResults
{
    #[Url(as: 'Area')]
    public $url_Area = '';

    protected function type(): string { return 'ambulance'; }

    protected function filter(Builder $query): Builder
    {
        return $query->search($this->url_Area, ['CurrentLocation', 'City']);
    }

    protected function heading(): array
    {
        $where = trim((string) $this->url_Area);

        return [$where !== '' ? 'Ambulances near ' . $where : 'Find an ambulance', 'Request the nearest available crew.', 'Search'];
    }
}
