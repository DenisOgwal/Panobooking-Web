<?php

namespace App\Livewire\Vehicles;

use App\Livewire\Concerns\SignsInInline;
use App\Models\CartModel;
use App\Support\Currency;
use App\Support\CustomerAuth;
use App\Support\ProviderNotifier;
use App\Support\Vehicles;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Shared booking flow for rental cars, airport taxis and ambulances: requires a signed-in
 * customer, validates, stores a pending cart row and notifies the provider.
 */
abstract class VehicleBooking extends Component
{
    use SignsInInline;

    abstract protected function type(): string;

    abstract protected function vehicleId();

    /** Cart columns specific to this booking (Cost, DatesFrom, DatesTo, Quantity, Descriptions…). */
    abstract protected function cartAttributes(): array;

    /** [script, params] for ProviderNotifier. */
    abstract protected function notification(): array;

    abstract protected function viewName(): string;

    #[Computed]
    public function vehicle(): ?Model
    {
        return Vehicles::query($this->type())->find($this->vehicleId());
    }

    public function mount(): void
    {
        abort_if(! $this->vehicle, 404);
        $this->prepare();
    }

    /** Set defaults after the vehicle is known. */
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
        $auth = app(CustomerAuth::class);
        if (! $auth->check()) {
            $this->addError('clientemail', 'Please sign in to complete your request.');

            return;
        }

        $this->validate();

        $currency = app(Currency::class);
        $attributes = $this->cartAttributes();
        $cost = (float) ($attributes['Cost'] ?? 0);

        CartModel::create($attributes + [
            'Facility' => $this->vehicle->DriverName ?: $this->vehicle->Company,
            'ProductName' => $this->vehicle->TaxiNo,
            'User' => $auth->email(),
            'UserNames' => $auth->name(),
            'OrderCode' => 'N/A',
            'Currency' => $currency->code(),
            'CurrencyAmount' => $currency->convert($cost),
        ]);

        [$script, $params] = $this->notification();
        app(ProviderNotifier::class)->send($script, $params);

        session()->flash('alert', ['type' => 'success', 'message' => 'Request sent! The provider will be in touch shortly.']);

        return $this->redirect('/BookingSuccess');
    }

    public function render()
    {
        $c = Vehicles::config($this->type());

        return view($this->viewName(), ['c' => $c, 'type' => $this->type()])
            ->layout('components.layouts.app', ['title' => 'Book ' . ($this->vehicle->CarSpec ?: $this->vehicle->TaxiNo)]);
    }
}
