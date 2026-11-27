<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TourPackages */
class TourPackageResource extends JsonResource
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
            'name' => $this->PackageName,
            'company' => $this->TravelCompany,
            'starts_in' => $this->City,
            'travel_date' => Present::date($this->TravelDate),
            'days' => Present::int($this->TripDays),
            'departure' => $this->TravelHours,
            'price_per_traveller' => Present::price($this->PackageFees2),
            'photo' => Present::photo('tour', $this->resource),
        ];

        return $this->detail ? $data + [
            'destinations' => $this->DestinationsVisted,
            'included' => $this->ServicesOffered,
            'activities' => $this->ExtraActivites,
            'gallery' => Present::gallery('tour', $this->resource),
        ] : $data;
    }
}
