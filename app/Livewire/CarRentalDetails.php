<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleDetail;
use Livewire\Attributes\Url;

/** /CarDetails?carslug={IDs} */
class CarRentalDetails extends VehicleDetail
{
    #[Url(as: 'carslug')]
    public $url_carslug;

    protected function type(): string { return 'car'; }

    protected function vehicleId() { return $this->url_carslug; }
}
