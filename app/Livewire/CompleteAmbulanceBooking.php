<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleBooking;
use Livewire\Attributes\Url;

/** /CompleteAmbulanceBooking?ambulancefinalslug={IDs} (rules: App\Bookings\Types\AmbulanceBooking) */
class CompleteAmbulanceBooking extends VehicleBooking
{
    #[Url(as: 'ambulancefinalslug')]
    public $url_ambulancefinalslug;

    public string $Area = '';
    public string $pickuppoint = '';
    public string $reason = '';
    public string $bookingnotes = '';
    public string $checkindate = '';
    public string $Pickuptime = '';

    protected function type(): string { return 'ambulance'; }

    protected function vehicleId() { return $this->url_ambulancefinalslug; }

    protected function viewName(): string { return 'livewire.complete-ambulance-booking'; }

    protected function bookingFields(): array
    {
        return ['area' => 'Area', 'pickup_point' => 'pickuppoint', 'reason' => 'reason', 'notes' => 'bookingnotes',
            'date' => 'checkindate', 'time' => 'Pickuptime'];
    }
}
