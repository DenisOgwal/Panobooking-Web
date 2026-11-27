<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A hotel / lodge / apartment (`pickuplocations`). The list shape; `StayDetailResource` adds
 * gallery, amenities, rooms and related stays.
 *
 * @mixin \App\Models\HotelsModel
 */
class StayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->IDs,
            'name' => $this->Place,
            'type' => $this->PlaceType,
            'city' => $this->City,
            'country' => $this->Country,
            'rating' => Present::float($this->Rating),
            'price_from' => Present::price($this->Prices),
            'price_details' => $this->PriceSpecifications,
            'summary' => $this->Comment,
            'photo' => Present::photo('hotel', $this->resource),
            'featured' => Present::yes($this->Featured),
            'trending' => Present::yes($this->Trending),
        ];
    }
}
