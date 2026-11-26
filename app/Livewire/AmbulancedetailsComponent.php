<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleDetail;
use Livewire\Attributes\Url;

/** /AmbulanceDetails?ambulanceslug={IDs} */
class AmbulancedetailsComponent extends VehicleDetail
{
    #[Url(as: 'ambulanceslug')]
    public $url_ambulanceslug;

    protected function type(): string { return 'ambulance'; }

    protected function vehicleId() { return $this->url_ambulanceslug; }
}
