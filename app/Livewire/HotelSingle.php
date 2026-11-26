<?php

namespace App\Livewire;

use App\Livewire\Concerns\SignsInInline;
use App\Models\HotelsModel;
use App\Support\Media;
use Livewire\Attributes\Url;
use Livewire\Component;

/** /HotelSingle?hotelslug={IDs}: hotel details with rooms. */
class HotelSingle extends Component
{
    use SignsInInline;

    #[Url(as: 'hotelslug')]
    public $url_hotelslug;

    public function render()
    {
        $hotel = HotelsModel::approved()
            ->with(HotelsModel::PROPERTY_DETAIL_RELATIONS)
            ->find($this->url_hotelslug);
        abort_if(! $hotel, 404);

        $rooms = $hotel->rooms()->with('roomdetails')->orderBy('Price')->get();
        $related = HotelsModel::approved()
            ->where('IDs', '!=', $hotel->IDs)
            ->where(fn ($q) => $q->where('PlaceType', $hotel->PlaceType)->orWhere('City', $hotel->City))
            ->orderBy('TrendRank')->limit(8)->get();

        return view('livewire.hotel-single', [
            'property' => $hotel,
            'kind' => 'hotel',
            'name' => $hotel->Place,
            'gallery' => Media::gallery('hotel', $hotel, 9),
            'rooms' => $rooms,
            'fromPrice' => $rooms->min('Price') ?: $hotel->Prices,
            'related' => $related,
            'bookUrl' => null,
        ])->layout('components.layouts.app', ['title' => $hotel->Place, 'description' => \Illuminate\Support\Str::limit((string) $hotel->Description, 155)]);
    }
}
