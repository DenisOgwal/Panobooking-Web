<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleLanding;

/** /cars */
class CarsPage extends VehicleLanding
{
    protected function type(): string { return 'car'; }

    protected function intro(): array
    {
        return ['Rent a car for the road ahead', 'Self-drive or chauffeured, from city saloons to safari-ready 4x4s.'];
    }
}
