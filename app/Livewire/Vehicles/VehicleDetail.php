<?php

namespace App\Livewire\Vehicles;

use App\Livewire\Concerns\SignsInInline;
use App\Models\CarIncludes;
use App\Support\Features;
use App\Support\Media;
use App\Support\Vehicles;
use Livewire\Component;

/** Detail page for a rental car, airport taxi or ambulance. */
abstract class VehicleDetail extends Component
{
    use SignsInInline;

    abstract protected function type(): string;

    abstract protected function vehicleId();

    public function render()
    {
        $type = $this->type();
        $c = Vehicles::config($type);

        $vehicle = Vehicles::query($type)->find($this->vehicleId());
        abort_if(! $vehicle, 404);

        $extras = match ($type) {
            'ambulance' => Features::fromModel($vehicle->Ambulancedetails),
            default => Features::fromModel(CarIncludes::where('Car', $vehicle->TaxiNo)->where('Company', $vehicle->DriverName)->first()),
        };

        $related = Vehicles::query($type)
            ->where($c['category'], $vehicle->{$c['category']})
            ->where('IDs', '!=', $vehicle->IDs)
            ->limit(8)->get();

        return view('livewire.vehicles.detail', [
            'type' => $type,
            'c' => $c,
            'vehicle' => $vehicle,
            'gallery' => Media::gallery($type, $vehicle, 9),
            'extras' => $extras,
            'notes' => $type === 'ambulance' ? $vehicle->Ambulancedetails?->MiscelaniousItems : null,
            'related' => $related,
        ])->layout('components.layouts.app', ['title' => ($vehicle->CarSpec ?: $vehicle->TaxiNo) . ' · ' . $c['label']]);
    }
}
