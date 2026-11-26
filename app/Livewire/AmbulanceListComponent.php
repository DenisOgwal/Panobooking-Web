<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleResults;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

/** /AmbulanceList?ambulancecategoriesslug={category} */
class AmbulanceListComponent extends VehicleResults
{
    #[Url(as: 'ambulancecategoriesslug')]
    public $url_ambulancecategoriesslug = '';

    protected function type(): string { return 'ambulance'; }

    protected function activeCategory(): ?string { return $this->url_ambulancecategoriesslug ?: null; }

    protected function filter(Builder $query): Builder
    {
        return $query->when($this->url_ambulancecategoriesslug, fn ($q, $cat) => $q->where('AmbulanceCategory', $cat));
    }

    protected function heading(): array
    {
        $cat = $this->url_ambulancecategoriesslug;

        return [$cat ? $cat . '' : 'All ambulances', 'Choose the level of care you need.', $cat ?: 'All'];
    }
}
