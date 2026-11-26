<?php

namespace App\Livewire;

use App\Models\events as EventModel;
use App\Support\Media;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /EventsDetails?searchslug={ID} */
class EventsDetails extends Component
{
    #[Url(as: 'searchslug')]
    public $url_searchslug;

    public function render()
    {
        $item = EventModel::find($this->url_searchslug);
        abort_if(! $item, 404);

        return view('livewire.experience-detail', [
            'item' => $item,
            'kind' => 'event',
            'gallery' => Media::gallery('event', $item, 9),
            'more' => EventModel::upcoming()->where('ID', '!=', $item->ID)->limit(4)->get(),
        ])->layout('components.layouts.app', ['title' => $item->EventName]);
    }
}
