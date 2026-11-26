<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleBooking;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

/** /CompleteCarBooking?carrentalfinalslug={IDs} */
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

    protected function prepare(): void
    {
        $this->checkindate = now()->addDay()->toDateString();
        $this->pickuppoint = (string) ($this->vehicle->CurrentLocation ?: $this->vehicle->City);
    }

    protected function rules(): array
    {
        return [
            'checkindate' => 'required|date|after_or_equal:today',
            'Duration' => 'required|integer|min:1|max:90',
            'Carsquantity' => 'required|integer|min:1|max:15',
            'driveloc' => 'required|in:Kampala,Upcountry',
            'pickuppoint' => 'required|string|min:2|max:200',
            'bookingnotes' => 'nullable|string|max:1000',
        ];
    }

    protected function validationAttributes(): array
    {
        return ['checkindate' => 'pick-up date', 'Duration' => 'days', 'Carsquantity' => 'cars', 'driveloc' => 'driving area', 'pickuppoint' => 'pick-up point', 'bookingnotes' => 'notes'];
    }

    /** Daily rate for the chosen driving area (upcountry uses PricePerDay when set). */
    #[Computed]
    public function rate(): float
    {
        $v = $this->vehicle;

        return (float) ($this->driveloc === 'Upcountry' && $v->PricePerDay ? $v->PricePerDay : $v->Price);
    }

    #[Computed]
    public function total(): float
    {
        return $this->rate * max(0, (int) $this->Carsquantity) * max(0, (int) $this->Duration);
    }

    protected function cartAttributes(): array
    {
        $from = Carbon::parse($this->checkindate);

        return [
            'Quantity' => (int) $this->Carsquantity,
            'Cost' => $this->total,
            'DatesFrom' => $from->toDateString(),
            'DatesTo' => $from->copy()->addDays((int) $this->Duration)->toDateString(),
            'BookingType' => 'Car Rental',
            'Descriptions' => trim("I will Pick the Car at {$this->pickuppoint}. DRIVING: {$this->driveloc}. NOTES: {$this->bookingnotes}"),
        ];
    }

    protected function notification(): array
    {
        $currency = app(\App\Support\Currency::class);

        return ['SendCar.php', [
            'hotelemail' => $this->vehicle->EmailAddress,
            'bookingnotes' => sprintf('Please reserve for %s %d x %s for %d day(s) from %s (%s driving). Pick-up: %s. TOTAL PAYABLE: %s. NOTES: %s',
                app(\App\Support\CustomerAuth::class)->name(), $this->Carsquantity, $this->vehicle->TaxiNo, $this->Duration,
                $this->checkindate, $this->driveloc, $this->pickuppoint, $currency->format($this->total), $this->bookingnotes),
        ]];
    }
}
