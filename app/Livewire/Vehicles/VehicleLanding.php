<?php

namespace App\Livewire\Vehicles;

use App\Support\Vehicles;
use Livewire\Component;

/** Landing page for a vehicle vertical (/cars, /taxis, /ambulance). */
abstract class VehicleLanding extends Component
{
    abstract protected function type(): string;

    abstract protected function intro(): array; // [title, lead]

    public function render()
    {
        $type = $this->type();
        [$title, $lead] = $this->intro();

        $vehicles = Vehicles::query($type)
            ->orderByRaw("Availability = 'Available' DESC")
            ->orderByDesc('Ratings')
            ->limit(12)->get();

        $offer = Vehicles::query($type)->offered()->orderByDesc('IDs')->first();

        return view('livewire.vehicles.landing', [
            'type' => $type,
            'c' => Vehicles::config($type),
            'title' => $title,
            'lead' => $lead,
            'vehicles' => $vehicles,
            'offer' => $offer,
            'categories' => Vehicles::categories($type),
        ])->layout('components.layouts.app', ['title' => Vehicles::config($type)['plural']]);
    }
}
