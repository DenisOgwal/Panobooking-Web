<?php

namespace App\Livewire;

use App\Models\TourismSite;
use Livewire\Component;
use Livewire\WithPagination;

/** /TourismSites */
class TourismSites extends Component
{
    use WithPagination;

    public function render()
    {
        $items = TourismSite::orderBy('SiteName')->paginate(12);

        return view('livewire.experiences', [
            'items' => $items,
            'kind' => 'site',
            'title' => 'Tourism sites',
            'subtitle' => 'Waterfalls, lakes, parks and heritage sites worth the trip.',
        ])->layout('components.layouts.app', ['title' => 'Tourism sites']);
    }
}
