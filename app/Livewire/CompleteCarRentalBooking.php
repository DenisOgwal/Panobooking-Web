<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleBooking;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

/** /CompleteCarBooking?carrentalfinalslug={IDs} (rules: App\Bookings\Types\CarBooking) */
class CompleteCarRentalBooking extends VehicleBooking
{
    #[Url(as: 'carrentalfinalslug')]
    public $url_carrentalfinalslug;

    public string $checkindate = '';
    public $Duration = 1;
    public $Carsquantity = 1;
    public string $driveloc = 'Kampala';
    public string $pickuppoint = '';
    public string $bookingnotes = '';

    protected function type(): string { return 'car'; }

    protected function vehicleId() { return $this->url_carrentalfinalslug; }

    protected function viewName(): string { return 'livewire.complete-car-rental-booking'; }

    protected function bookingFields(): array
    {
        return ['pickup_date' => 'checkindate', 'days' => 'Duration', 'cars' => 'Carsquantity', 'driving_area' => 'driveloc',
            'pickup_point' => 'pickuppoint', 'notes' => 'bookingnotes'];
    }

    /** Daily rate for the chosen driving area (upcountry uses PricePerDay when set). */
    #[Computed]
    public function rate(): float
    {
        return $this->bookingType()->rate($this->vehicle, $this->driveloc);
    }

    #[Computed]
    public function total(): float
    {
        return $this->bookingTotal();
    }
}
