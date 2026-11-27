<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A room type of a stay (`rooms`), bookable with POST /bookings/room/{id}.
 *
 * @mixin \App\Models\RoomsModel
 */
class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->IDs,
            'name' => $this->RoomName,
            'stay' => $this->whenLoaded('hotel', fn () => $this->hotel ? ['id' => $this->hotel->IDs, 'name' => $this->hotel->Place, 'city' => $this->hotel->City] : null,
                ['name' => $this->HotelName]),
            'description' => $this->RoomDesc,
            'price' => Present::price($this->Price),
            'rooms_available' => max(1, (int) $this->NoOfRooms),
            // Free text in the data ("Included", "Yes", "Extra"…), as the website shows it.
            'breakfast' => $this->BreakFast,
            'breakfast_included' => filled($this->BreakFast) && ! in_array(strtolower(trim((string) $this->BreakFast)), ['no', 'not included', 'none'], true),
            'refund_policy' => $this->RefundPolicy,
            'available' => $this->Availability === 'Available',
            'on_offer' => $this->Offered === 'Offered',
            'photo' => Present::photo('room', $this->resource),
            'gallery' => Present::gallery('room', $this->resource),
            'size' => $this->whenLoaded('roomdetails', fn () => $this->roomdetails?->Feet),
            'features' => $this->whenLoaded('roomdetails', fn () => Present::features($this->roomdetails)),
        ];
    }
}
