<?php

namespace App\Livewire\Vehicles;

use App\Livewire\Concerns\UsesBookingType;
use App\Support\Vehicles;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Shared booking page for rental cars, airport taxis and ambulances. Rules, price and saving
 * live in App\Bookings\Types\{Car,Taxi,Ambulance}Booking, shared with the mobile API.
 */
abstract class VehicleBooking extends Component
{
    use UsesBookingType;

    /** car | taxi | ambulance (also the booking type key). */
    abstract protected function type(): string;

    abstract protected function vehicleId();

    abstract protected function viewName(): string;

    protected function bookingKey(): string { return $this->type(); }

    protected function bookable(): ?Model { return $this->vehicle; }

    #[Computed]
    public function vehicle(): ?Model
    {
        return $this->bookingType()->find($this->vehicleId());
    }

    public function mount(): void
    {
        abort_if(! $this->vehicle, 404);
        $this->applyBookingDefaults($this->keepOnMount());
        $this->prepare();
    }

    /** API fields whose value came from the URL and must survive the defaults. */
    protected function keepOnMount(): array
    {
        return [];
    }

    /** Adjust values after the defaults are applied. */
    protected function prepare(): void
    {
    }

    public function updated($field): void
    {
        if (array_key_exists($field, $this->rules())) {
            $this->validateOnly($field);
        }
    }

    public function book()
    {
        return $this->placeBooking('Request sent! The provider will be in touch shortly.');
    }

    public function render()
    {
        $c = Vehicles::config($this->type());

        return view($this->viewName(), ['c' => $c, 'type' => $this->type()])
            ->layout('components.layouts.app', ['title' => 'Book ' . ($this->vehicle->CarSpec ?: $this->vehicle->TaxiNo)]);
    }
}
