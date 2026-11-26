<?php

namespace App\Livewire;

use App\Livewire\Concerns\SortsListings;
use App\Models\HotelsModel;
use App\Support\Facets;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /DestinationsList?destinationslug={City}: approved stays, optionally in one city. */
class DetinationsListComponent extends Component
{
    use SortsListings;

    #[Url(as: 'destinationslug')]
    public $url_destinationslug = '';

    public function render()
    {
        $city = trim((string) $this->url_destinationslug);

        $results = $this->applySort(
            HotelsModel::approved()->when($city !== '', fn ($q) => $q->where('City', $city)),
            'Prices', 'Place', 'Rating', 'TrendRank'
        )->paginate($this->perPage);

        return view('livewire.detinations-list-component', [
            'results' => $results,
            'kind' => 'hotel',
            'title' => $city !== '' ? "Stays in {$city}" : 'All stays',
            'subtitle' => $city !== '' ? "Hotels, lodges and apartments in {$city}." : 'Hotels, lodges, resorts and apartments across Uganda.',
            'crumbs' => array_filter([['Stays', '/DestinationsList'], $city !== '' ? [$city] : null]),
            'facets' => Facets::hotelCities(),
            'activeFacet' => $city,
            'facetUrl' => fn (?string $c) => $c ? route('DestinationsList', ['destinationslug' => $c]) : route('DestinationsList'),
            'sortOptions' => $this->sortOptions(),
        ])->layout('components.layouts.app', ['title' => $city !== '' ? "Stays in {$city}" : 'All stays']);
    }
}
