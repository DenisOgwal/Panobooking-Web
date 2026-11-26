<?php

namespace App\Livewire;

use App\Livewire\Vehicles\VehicleBooking;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;

/** /CompleteAmbulanceBooking?ambulancefinalslug={IDs} */
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

    protected function prepare(): void
    {
        $this->checkindate = now()->toDateString();
        $this->Pickuptime = now()->addMinutes(30)->format('H:i');
        $this->Area = (string) ($this->vehicle->CurrentLocation ?: $this->vehicle->City);
    }

    protected function rules(): array
    {
        return [
            'Area' => 'required|string|min:2|max:150',
            'pickuppoint' => 'required|string|min:2|max:200',
            'reason' => 'required|string|min:10|max:1000',
            'bookingnotes' => 'nullable|string|max:1000',
            'checkindate' => 'required|date|after_or_equal:today',
            'Pickuptime' => 'required|date_format:H:i',
        ];
    }

    protected function validationAttributes(): array
    {
        return ['Area' => 'area', 'pickuppoint' => 'exact pick-up point', 'reason' => 'reason', 'bookingnotes' => 'notes', 'checkindate' => 'date', 'Pickuptime' => 'time'];
    }

    protected function cartAttributes(): array
    {
        return [
            'Quantity' => 1,
            'Cost' => 0,
            'DatesFrom' => Carbon::parse($this->checkindate)->toDateString() . ' ' . $this->Pickuptime,
            'DatesTo' => 'N/A',
            'BookingType' => 'Ambulance',
            'Descriptions' => "Please Pick me up from {$this->Area}. EXACT PICKUPPOINT: {$this->pickuppoint}. REASON: {$this->reason}. NOTES: {$this->bookingnotes}",
        ];
    }

    protected function notification(): array
    {
        return ['SendAmbulance.php', [
            'hotelemail' => $this->vehicle->EmailAddress,
            'bookingnotes' => sprintf('Please help %s. Ambulance %s. Pick-up %s at %s from %s (exact point: %s). REASON: %s. NOTES: %s',
                app(\App\Support\CustomerAuth::class)->name(), $this->vehicle->TaxiNo, $this->checkindate, $this->Pickuptime,
                $this->Area, $this->pickuppoint, $this->reason, $this->bookingnotes),
        ]];
    }
}
