<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleResults;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

/** /CarsList?carcategoriesslug={category} */
class CarsListComponent extends VehicleResults
{
    #[Url(as: 'carcategoriesslug')]
    public $url_carcategoriesslug = '';

    protected function type(): string { return 'car'; }

    protected function activeCategory(): ?string { return $this->url_carcategoriesslug ?: null; }

    protected function filter(Builder $query): Builder
    {
        return $query->when($this->url_carcategoriesslug, fn ($q, $cat) => $q->where('CarCategory', $cat));
    }

    protected function heading(): array
    {
        $cat = $this->url_carcategoriesslug;

        return [$cat ? $cat . ' rentals' : 'All rental cars', 'Compare cars, specs and daily rates.', $cat ?: 'All'];
    }
}
