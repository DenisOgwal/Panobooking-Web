<?php

namespace App\Livewire;

use App\Models\TourismSite;
use App\Support\Media;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /TourismSiteDetails?searchslug={ID} */
class Tourismsitedetails extends Component
{
    #[Url(as: 'searchslug')]
    public $url_searchslug;

    public function render()
    {
        $item = TourismSite::find($this->url_searchslug);
        abort_if(! $item, 404);

        return view('livewire.experience-detail', [
            'item' => $item,
            'kind' => 'site',
            'gallery' => Media::gallery('site', $item, 9),
            'more' => TourismSite::query()->where('ID', '!=', $item->ID)->limit(4)->get(),
        ])->layout('components.layouts.app', ['title' => $item->SiteName]);
    }
}
