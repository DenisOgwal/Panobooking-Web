<?php

namespace App\Livewire;

use App\Livewire\Concerns\SortsListings;
use App\Models\HotelsModel;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /PropertyTypes?propertyslug={Hotel|Lodge|…}: stays of one property type. */
class PropertyTypes extends Component
{
    use SortsListings;

    #[Url(as: 'propertyslug')]
    public $url_propertyslug = '';

    public function render()
    {
        $type = trim((string) $this->url_propertyslug);
        // Accept plural forms used by older links ("Apartments", "Guest Houses").
        $types = array_values(array_unique(array_filter([$type, Str::singular($type)])));

        $results = $this->applySort(
            HotelsModel::approved()->when($types, fn ($q) => $q->whereIn('PlaceType', $types)),
            'Prices', 'Place', 'Rating', 'FaturedRank'
        )->paginate($this->perPage);

        $label = $type !== '' ? Str::plural(Str::singular($type)) : 'All property types';

        return view('livewire.property-types', [
            'results' => $results,
            'kind' => 'hotel',
            'title' => $label,
            'subtitle' => $type !== '' ? 'Every ' . Str::lower(Str::singular($type)) . ' on Panobooking, in one place.' : null,
            'crumbs' => [['Stays', '/DestinationsList'], [$label]],
            'facets' => collect(),
            'activeFacet' => null,
            'facetUrl' => fn (?string $c) => route('DestinationsList'),
            'sortOptions' => $this->sortOptions(),
        ])->layout('components.layouts.app', ['title' => $label]);
    }
}
