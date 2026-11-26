<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleResults;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

/** /TaxisList?taxicategoriesslug={category} */
class TaxiListComponent extends VehicleResults
{
    #[Url(as: 'taxicategoriesslug')]
    public $url_taxicategoriesslug = '';

    protected function type(): string { return 'taxi'; }

    protected function activeCategory(): ?string { return $this->url_taxicategoriesslug ?: null; }

    protected function filter(Builder $query): Builder
    {
        return $query->when($this->url_taxicategoriesslug, fn ($q, $cat) => $q->where('CarCategory', $cat));
    }

    protected function heading(): array
    {
        $cat = $this->url_taxicategoriesslug;

        return [$cat ? $cat . ' airport taxis' : 'All airport taxis', 'Licensed drivers, fixed prices, meet and greet at arrivals.', $cat ?: 'All'];
    }
}
