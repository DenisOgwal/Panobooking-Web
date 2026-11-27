<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A home stay (`homeguesthouses`), booked whole with POST /bookings/home-stay/{id}.
 * With `?detail` semantics the controller sets $detail to add the full page data.
 *
 * @mixin \App\Models\HomeGuestModel
 */
class HomeStayResource extends JsonResource
{
    public bool $detail = false;

    public function detailed(): static
    {
        $this->detail = true;

        return $this;
    }

    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->IDs,
            'name' => $this->GuestHouseName,
            'city' => $this->City,
            'area' => $this->Place,
            'rating' => Present::float($this->Rating),
            'price_per_night' => Present::price($this->Prices),
            'rooms' => Present::int($this->NoOfRooms),
            'available' => $this->Availability === 'Available',
            'on_offer' => $this->Offered === 'Offered',
            'photo' => Present::photo('guesthouse', $this->resource),
        ];

        if (! $this->detail) {
            return $data;
        }

        return $data + [
            'description' => $this->Description,
            'address' => $this->ExactLocation,
            'coordinates' => Present::coordinates($this->Coordinates),
            'gallery' => Present::gallery('guesthouse', $this->resource),
            'policies' => [
                'breakfast_included' => Present::yes($this->BedBreakFast),
                'cards_accepted' => Present::yes($this->CreditCard),
                'parking' => $this->Parking,
            ],
            'room_features' => Present::features($this->roomdetails),
            'details' => Present::propertyDetails((string) $this->GuestHouseName),
        ];
    }
}
