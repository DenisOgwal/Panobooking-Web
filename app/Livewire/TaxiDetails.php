<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleDetail;
use Livewire\Attributes\Url;

/** /TaxiDetails?taxislug={IDs} */
class TaxiDetails extends VehicleDetail
{
    #[Url(as: 'taxislug')]
    public $url_taxislug;

    protected function type(): string { return 'taxi'; }

    protected function vehicleId() { return $this->url_taxislug; }
}
