<?php

namespace App\Livewire;

use App\Models\AmbulanceModel;
use App\Models\events as EventModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;
use App\Models\TourismSite;
use App\Models\TourPackages;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /generalSearchs?salessearch=…: one search across every kind of listing. */
class GeneralSearch extends Component
{
    private const LIMIT = 12;

    #[Url(as: 'salessearch')]
    public $url_salessearch = '';

    public function render()
    {
        $term = trim((string) $this->url_salessearch);

        $data = [
            'term' => $term,
            'hotelsearched' => HotelsModel::approved()->search($term, HotelsModel::SEARCH_COLUMNS)->orderByRaw('FaturedRank IS NULL')->orderBy('FaturedRank')->limit(self::LIMIT)->get(),
            'carsearched' => RentalCarModel::approved()->search($term, RentalCarModel::SEARCH_COLUMNS)->latest('updated_at')->limit(self::LIMIT)->get(),
            'taxisearched' => TaxiModel::when(! TaxiModel::servesDestination($term), fn ($q) => $q->search($term, TaxiModel::SEARCH_COLUMNS))->latest('updated_at')->limit(self::LIMIT)->get(),
            'ambulancesearched' => AmbulanceModel::approved()->search($term, AmbulanceModel::SEARCH_COLUMNS)->latest('updated_at')->limit(self::LIMIT)->get(),
            'tourpackagesearched' => TourPackages::upcoming()->search($term, TourPackages::SEARCH_COLUMNS)->orderBy('TravelDate')->limit(self::LIMIT)->get(),
            'tourismsites' => TourismSite::search($term, TourismSite::SEARCH_COLUMNS)->orderBy('SiteName')->limit(self::LIMIT)->get(),
            'eventss' => EventModel::upcoming()->search($term, EventModel::SEARCH_COLUMNS)->orderBy('StartDate')->limit(self::LIMIT)->get(),
        ];

        return view('livewire.general-search', $data)
            ->layout('components.layouts.app', ['title' => $term !== '' ? "Search: {$term}" : 'Search']);
    }
}
