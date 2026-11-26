<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleResults;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

/** /CarSearch?pickuplocation=... */
class CarSearch extends VehicleResults
{
    #[Url(as: 'pickuplocation')]
    public $url_pickuplocation = '';

    protected function type(): string { return 'car'; }

    protected function filter(Builder $query): Builder
    {
        return $query->search($this->url_pickuplocation, ['CurrentLocation', 'City']);
    }

    protected function heading(): array
    {
        $where = trim((string) $this->url_pickuplocation);

        return [$where !== '' ? 'Cars near ' . $where : 'Find a rental car', 'Pick-up and drop-off where it suits you.', 'Search'];
    }
}
