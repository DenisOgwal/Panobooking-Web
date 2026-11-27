<?php

namespace App\Livewire;

use App\Bookings\Types\TaxiBooking;
use App\Livewire\Vehicles\VehicleBooking;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

/** /CompleteTaxiBooking?taxifinalslug={IDs}&Destination=… (rules: App\Bookings\Types\TaxiBooking) */
class CompleteTaxiBooking extends VehicleBooking
{
    #[Url(as: 'taxifinalslug')]
    public $url_taxifinalslug;

    #[Url(as: 'Destination', except: '')]
    public string $Destination = '';

    public string $pickuppoint = 'From Airport';
    public string $airportfield = 'Entebbe';
    public $NoOfPeople = 1;
    public string $checkindate = '';
    public string $pickuptime = '';
    public string $bookingnotes = '';

    protected function type(): string { return 'taxi'; }

    protected function vehicleId() { return $this->url_taxifinalslug; }

    protected function viewName(): string { return 'livewire.complete-taxi-booking'; }

    protected function bookingFields(): array
    {
        return ['trip' => 'pickuppoint', 'airport' => 'airportfield', 'destination' => 'Destination', 'passengers' => 'NoOfPeople',
            'date' => 'checkindate', 'time' => 'pickuptime', 'notes' => 'bookingnotes'];
    }

    protected function keepOnMount(): array
    {
        return ['destination'];
    }

    protected function prepare(): void
    {
        if ($this->Destination === '' || ! $this->destinations->contains($this->Destination)) {
            $this->Destination = (string) $this->destinations->first();
        }
    }

    /** Destinations served from the chosen airport. */
    #[Computed]
    public function destinations()
    {
        return TaxiBooking::destinations($this->airportfield);
    }

    /** Fixed fare (UGX) for the chosen route. */
    #[Computed]
    public function fare(): ?float
    {
        return TaxiBooking::fare($this->airportfield, $this->Destination);
    }
}
