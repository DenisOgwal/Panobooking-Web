<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;

/**
 * One stay with everything its website page shows (load `rooms.roomdetails` first).
 * Related stays are returned next to it by the controller.
 *
 * @mixin \App\Models\HotelsModel
 */
class StayDetailResource extends StayResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'description' => $this->Description,
            'address' => $this->ExactLocation,
            'coordinates' => Present::coordinates($this->coordinates),
            'gallery' => Present::gallery('hotel', $this->resource),
            'policies' => [
                'taxes_included' => Present::yes($this->TaxesInclusive),
                'prepayment_required' => Present::yes($this->Prepayment),
                'cards_accepted' => Present::yes($this->CreditCard),
                'breakfast_included' => Present::yes($this->BedBreakFast),
                'parking' => $this->Parking,
            ],
            'details' => Present::propertyDetails((string) $this->Place),
            'rooms' => RoomResource::collection($this->whenLoaded('rooms')),
        ];
    }
}
