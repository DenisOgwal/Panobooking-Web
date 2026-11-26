<?php

namespace App\Livewire;

use App\Models\DestinationsModel;
use App\Support\Facets;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /Cities?cityslug={Region}&countryslug={Country}: cities in a region (or all cities). */
class Cities extends Component
{
    // Historical naming: "cityslug" carries the region name on this page.
    #[Url(as: 'cityslug')]
    public $url_cityslug = '';

    #[Url(as: 'countryslug')]
    public $url_countryslug = '';

    public function render()
    {
        $region = trim((string) $this->url_cityslug);
        $country = trim((string) $this->url_countryslug);

        $cities = DestinationsModel::query()
            ->when($region !== '', fn ($q) => $q->where('Region', $region))
            ->when($country !== '', fn ($q) => $q->where('Country', $country))
            ->orderBy('City')
            ->get();

        $stays = Facets::hotelCities();
        $title = $region !== '' ? "Cities in {$region}" : 'All cities';

        return view('livewire.cities', compact('cities', 'stays', 'region', 'title'))
            ->layout('components.layouts.app', ['title' => $title]);
    }
}
