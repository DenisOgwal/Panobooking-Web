<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleLanding;

/** /taxis */
class TaxisPAge extends VehicleLanding
{
    protected function type(): string { return 'taxi'; }

    protected function intro(): array
    {
        return ['Airport transfers, sorted', 'Fixed-price taxis between Entebbe International Airport and your destination.'];
    }
}
