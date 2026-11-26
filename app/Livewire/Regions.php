<?php

namespace App\Livewire;

use App\Models\DestinationsModel;
use Livewire\Component;

/** /Regions: regions grouped by country, linking to their cities. */
class Regions extends Component
{
    public function render()
    {
        $regions = DestinationsModel::query()
            ->whereNotNull('Region')
            ->selectRaw('Country, Region, COUNT(*) as cities')
            ->groupBy('Country', 'Region')
            ->orderBy('Country')->orderBy('Region')
            ->get()
            ->groupBy('Country');

        return view('livewire.regions', ['regions' => $regions])
            ->layout('components.layouts.app', ['title' => 'Regions']);
    }
}
