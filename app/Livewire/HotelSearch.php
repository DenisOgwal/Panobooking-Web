<?php

namespace App\Livewire;

use App\Livewire\Concerns\SortsListings;
use App\Models\HotelsModel;
use App\Support\Facets;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /HotelSearch?from_city=…: stays whose city or search terms match. */
class HotelSearch extends Component
{
    use SortsListings;

    #[Url(as: 'from_city')]
    public $url_from_city = '';

    public function render()
    {
        $term = trim((string) $this->url_from_city);

        $results = $this->applySort(
            HotelsModel::approved()->search($term, ['City', 'Place', 'SearchTerms']),
            'Prices', 'Place', 'Rating', 'TrendRank'
        )->paginate($this->perPage);

        return view('livewire.hotel-search', [
            'results' => $results,
            'kind' => 'hotel',
            'title' => $term !== '' ? "Stays matching “{$term}”" : 'Find a stay',
            'subtitle' => 'Compare prices and amenities, then book directly.',
            'crumbs' => [['Stays', '/DestinationsList'], ['Search']],
            'facets' => Facets::hotelCities(),
            'activeFacet' => Facets::hotelCities()->keys()->first(fn ($c) => strcasecmp($c, $term) === 0),
            'facetUrl' => fn (?string $c) => $c ? route('DestinationsList', ['destinationslug' => $c]) : route('DestinationsList'),
            'sortOptions' => $this->sortOptions(),
        ])->layout('components.layouts.app', ['title' => 'Search stays']);
    }
}
