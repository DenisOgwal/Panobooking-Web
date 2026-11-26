<?php

namespace App\Livewire;

use App\Models\AmbulanceModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /Properties?cityslug={City}&countryslug={Country}: everything bookable in one city. */
class Properties extends Component
{
    #[Url(as: 'cityslug')]
    public $url_cityslug = '';

    #[Url(as: 'countryslug')]
    public $url_countryslug = '';

    public function render()
    {
        $city = trim((string) $this->url_cityslug);
        $country = trim((string) $this->url_countryslug);

        $hotels = HotelsModel::approved()->where('City', $city)
            ->when($country !== '', fn ($q) => $q->where('Country', $country))
            ->orderByRaw('TrendRank IS NULL')->orderBy('TrendRank')->limit(24)->get();
        $cars = RentalCarModel::approved()->where('City', $city)->limit(12)->get();
        $taxis = TaxiModel::forDestination($city)->limit(12)->get();
        $ambulances = AmbulanceModel::approved()->where('City', $city)->limit(12)->get();

        return view('livewire.properties', compact('city', 'country', 'hotels', 'cars', 'taxis', 'ambulances'))
            ->layout('components.layouts.app', ['title' => $city !== '' ? "Travel in {$city}" : 'Destinations']);
    }
}
