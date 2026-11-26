<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleLanding;

/** /ambulance */
class AmbulancePage extends VehicleLanding
{
    protected function type(): string { return 'ambulance'; }

    protected function intro(): array
    {
        return ['Request an ambulance', 'Equipped ambulances and trained crews near you. In an emergency, call your local emergency number first.'];
    }
}
