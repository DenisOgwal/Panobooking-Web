<?php

namespace App\Livewire;

use App\Livewire\Concerns\SignsInInline;
use App\Models\HomeGuestModel;
use App\Support\Media;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /HomeGuestSingle?homeguestslug={IDs}: guest house details (booked as a whole). */
class GuestHouseDetails extends Component
{
    use SignsInInline;

    #[Url(as: 'homeguestslug')]
    public $url_homeguestslug;

    public function render()
    {
        $house = HomeGuestModel::with(HomeGuestModel::PROPERTY_DETAIL_RELATIONS)->find($this->url_homeguestslug);
        abort_if(! $house, 404);

        $related = HomeGuestModel::where('IDs', '!=', $house->IDs)->orderByDesc('IDs')->limit(8)->get();

        return view('livewire.guest-house-details', [
            'property' => $house,
            'kind' => 'guesthouse',
            'name' => $house->GuestHouseName,
            'gallery' => Media::gallery('guesthouse', $house, 9),
            'rooms' => collect(),
            'fromPrice' => $house->Prices,
            'related' => $related,
            'bookUrl' => route('CompleteHomeGuestBooking', ['homeguestfinalslug' => $house->IDs]),
        ])->layout('components.layouts.app', ['title' => $house->GuestHouseName]);
    }
}
