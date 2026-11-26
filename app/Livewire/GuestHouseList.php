<?php

namespace App\Livewire;

use App\Livewire\Concerns\SortsListings;
use App\Models\HomeGuestModel;
use App\Support\Facets;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /HomeGuestsList?destinationslug={City}: guest houses, optionally in one city. */
class GuestHouseList extends Component
{
    use SortsListings;

    #[Url(as: 'destinationslug')]
    public $url_destinationslug = '';

    protected string $pageTitle = 'Home stays';

    protected function query(string $city)
    {
        return HomeGuestModel::query()->when($city !== '', fn ($q) => $q->where('City', $city));
    }

    public function render()
    {
        $city = trim((string) $this->url_destinationslug);
        $results = $this->applySort($this->query($city), 'Prices', 'GuestHouseName')->paginate($this->perPage);
        $title = $city !== '' ? "Home stays in {$city}" : $this->pageTitle;

        return view('livewire.guest-house-list', [
            'results' => $results,
            'kind' => 'guesthouse',
            'title' => $title,
            'subtitle' => 'Stay with local hosts: home-cooked meals, quiet gardens and a warm welcome.',
            'crumbs' => array_filter([['Home stays', '/homeguests'], $city !== '' ? [$city] : null]),
            'facets' => Facets::guestHouseCities(),
            'activeFacet' => $city,
            'facetUrl' => fn (?string $c) => $c ? route('HomeGuestsList', ['destinationslug' => $c]) : route('HomeGuestsList'),
            'sortOptions' => $this->sortOptions(),
        ])->layout('components.layouts.app', ['title' => $title]);
    }
}
