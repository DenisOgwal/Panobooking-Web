<?php

namespace App\Http\Resources\V1;

use App\Models\AmbulanceModel;
use App\Models\CarIncludes;
use App\Models\TaxiModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A rental car, airport taxi or ambulance. Call ->detailed() to add gallery, extras and notes.
 *
 * @mixin \App\Models\RentalCarModel|\App\Models\TaxiModel|\App\Models\AmbulanceModel
 */
class VehicleResource extends JsonResource
{
    public bool $detail = false;

    public function detailed(): static
    {
        $this->detail = true;

        return $this;
    }

    public static function typeOf($model): string
    {
        return match (true) {
            $model instanceof AmbulanceModel => 'ambulance',
            $model instanceof TaxiModel => 'taxi',
            default => 'car',
        };
    }

    public function toArray(Request $request): array
    {
        $type = self::typeOf($this->resource);
        $isAmbulance = $type === 'ambulance';

        $data = [
            'id' => $this->IDs,
            'type' => $type,
            'plate' => $this->TaxiNo,
            'name' => $isAmbulance ? trim($this->AmbulanceCategory . ' ambulance') : ($this->CarSpec ?: $this->TaxiNo),
            'category' => $isAmbulance ? $this->AmbulanceCategory : $this->CarCategory,
            'provider' => $type === 'car' ? ($this->Company ?: $this->DriverName) : $this->DriverName,
            'location' => $this->CurrentLocation ?: $this->City,
            'city' => $this->City,
            'rating' => Present::float($this->Ratings),
            'available' => $this->Availability === 'Available',
            'on_offer' => $this->Offered === 'Offered',
            'photo' => Present::photo($type, $this->resource),
        ];

        $data += match ($type) {
            'car' => [
                'price_per_day' => Present::price($this->Price),
                'price_per_day_upcountry' => Present::price($this->PricePerDay ?: $this->Price),
            ],
            'taxi' => ['price_from' => Present::price($this->Price)],
            default => ['price' => (float) $this->Price > 0 ? Present::price($this->Price) : null],
        };

        if (! $isAmbulance) {
            $data += [
                'model' => $this->CarSpec,
                'passengers' => Present::int($this->Passengers),
                'doors' => Present::int($this->Doors),
                'luggage' => Present::int($this->Laggages),
                'automatic' => Present::yes($this->Automatic),
            ];
            if ($type === 'car') {
                $data['min_driver_age'] = Present::int($this->DriverAge);
            }
        }

        if (! $this->detail) {
            return $data;
        }

        return $data + [
            'description' => $isAmbulance ? $this->CarSpec : $this->Description,
            'gallery' => Present::gallery($type, $this->resource),
            'extras' => $isAmbulance
                ? Present::features($this->Ambulancedetails)
                : Present::features(CarIncludes::where('Car', $this->TaxiNo)->where('Company', $this->DriverName)->first()),
            'notes' => $isAmbulance ? $this->Ambulancedetails?->MiscelaniousItems : null,
        ];
    }
}
