<?php

namespace App\Livewire;

use App\Livewire\Concerns\FindsNearbyListings;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /EventsProperties?cityslug=&siteslug=&searchslug= */
class EventsProperties extends Component
{
    use FindsNearbyListings;

    #[Url(as: 'cityslug')]
    public $url_cityslug = '';

    #[Url(as: 'siteslug')]
    public $url_siteslug = '';

    #[Url(as: 'searchslug')]
    public $url_searchslug = '';

    public function render()
    {
        $city = trim((string) $this->url_cityslug);
        $name = trim((string) $this->url_siteslug);

        return view('livewire.properties', $this->nearbyListings($city, [$name, $this->url_searchslug]) + [
            'city' => $city,
            'country' => '',
            'heading' => $name !== '' ? 'Stay near ' . $name : ($city !== '' ? 'Travel in ' . $city : 'Stay near events'),
            'subheading' => 'Stays, cars, taxis and ambulances close to this event.',
            'crumbs' => [['Events', '/Events'], [$name ?: $city ?: 'Nearby']],
        ])->layout('components.layouts.app', ['title' => $name !== '' ? 'Stay near ' . $name : 'Stay near events']);
    }
}
