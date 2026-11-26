<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleBooking;
use App\Models\TaxiTripModel;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

/** /CompleteTaxiBooking?taxifinalslug={IDs}&Destination=… */
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

    protected function prepare(): void
    {
        $this->checkindate = now()->addDay()->toDateString();
        $this->pickuptime = '10:00';
        if ($this->Destination === '' || ! $this->destinations->contains($this->Destination)) {
            $this->Destination = (string) $this->destinations->first();
        }
    }

    /** Destinations served from the chosen airport. */
    #[Computed]
    public function destinations()
    {
        return TaxiTripModel::where('Froms', 'like', $this->airportfield . '%')->orderBy('Tos')->pluck('Tos')->unique()->values();
    }

    /** Fixed fare (UGX) for the chosen route. */
    #[Computed]
    public function fare(): ?float
    {
        $price = TaxiTripModel::where('Froms', 'like', $this->airportfield . '%')->where('Tos', $this->Destination)->value('Price');

        return $price !== null ? (float) $price : null;
    }

    protected function rules(): array
    {
        return [
            'pickuppoint' => 'required|in:From Airport,To Airport',
            'airportfield' => 'required|string',
            'Destination' => 'required|string',
            'NoOfPeople' => 'required|integer|min:1|max:14',
            'checkindate' => 'required|date|after_or_equal:today',
            'pickuptime' => 'required|date_format:H:i',
            'bookingnotes' => 'nullable|string|max:1000',
        ];
    }

    protected function validationAttributes(): array
    {
        return ['pickuppoint' => 'trip', 'airportfield' => 'airport', 'NoOfPeople' => 'passengers', 'checkindate' => 'date', 'pickuptime' => 'time', 'bookingnotes' => 'notes'];
    }

    private function routeDescription(): string
    {
        return $this->pickuppoint === 'From Airport'
            ? "Pick Me up from {$this->airportfield} Airport to {$this->Destination}"
            : "Pick Me up from {$this->Destination} to {$this->airportfield} Airport";
    }

    protected function cartAttributes(): array
    {
        return [
            'Quantity' => 1,
            'Cost' => $this->fare ?? 0,
            'DatesFrom' => Carbon::parse($this->checkindate)->toDateString() . ' ' . $this->pickuptime,
            'DatesTo' => 'N/A',
            'BookingType' => 'Airport Taxi',
            'Descriptions' => $this->routeDescription() . '. NO. OF PEOPLE: ' . (int) $this->NoOfPeople . '. NOTES: ' . $this->bookingnotes,
        ];
    }

    protected function notification(): array
    {
        return ['SendTaxi.php', [
            'hotelemail' => $this->vehicle->EmailAddress,
            'bookingnotes' => sprintf('%s for %s on %s at %s. Taxi %s. People: %d. FARE: %s. NOTES: %s',
                $this->routeDescription(), app(\App\Support\CustomerAuth::class)->name(), $this->checkindate, $this->pickuptime,
                $this->vehicle->TaxiNo, $this->NoOfPeople, app(\App\Support\Currency::class)->format($this->fare ?? 0), $this->bookingnotes),
        ]];
    }
}
