<?php

namespace App\Livewire;

use App\Livewire\Concerns\SortsListings;
use App\Models\HomeGuestModel;
use App\Support\Facets;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /HomeGuestSearch?Destination=…: guest houses whose name, town or area matches. */
class HomeGuestSearch extends Component
{
    use SortsListings;

    #[Url(as: 'Destination')]
    public $url_Destination = '';

    public function render()
    {
        $term = trim((string) $this->url_Destination);
        $results = $this->applySort(
            HomeGuestModel::search($term, HomeGuestModel::SEARCH_COLUMNS),
            'Prices', 'GuestHouseName'
        )->paginate($this->perPage);

        return view('livewire.home-guest-search', [
            'results' => $results,
            'kind' => 'guesthouse',
            'title' => $term !== '' ? "Home stays matching “{$term}”" : 'Find a home stay',
            'subtitle' => 'Stay with local hosts across Uganda.',
            'crumbs' => [['Home stays', '/homeguests'], ['Search']],
            'facets' => Facets::guestHouseCities(),
            'activeFacet' => null,
            'facetUrl' => fn (?string $c) => $c ? route('HomeGuestsList', ['destinationslug' => $c]) : route('HomeGuestsList'),
            'sortOptions' => $this->sortOptions(),
        ])->layout('components.layouts.app', ['title' => 'Search home stays']);
    }
}
