<?php

namespace App\Livewire\Concerns;

use App\Bookings\BookingService;
use App\Bookings\BookingType;
use App\Bookings\Customer;
use App\Support\Currency;
use App\Support\CustomerAuth;
use Illuminate\Database\Eloquent\Model;

/**
 * Website booking forms on top of App\Bookings: the page keeps its historical property names
 * (bookingFields() maps API field => property) and gets the shared rules, price and saving.
 */
trait UsesBookingType
{
    use SignsInInline;

    /** @return array<string, string> API field name => component property */
    abstract protected function bookingFields(): array;

    abstract protected function bookingKey(): string;

    /** The room, house, vehicle or package being booked. */
    abstract protected function bookable(): ?Model;

    protected function bookingType(): BookingType
    {
        return app(BookingService::class)->type($this->bookingKey());
    }

    private function bookingProperty(string $field): string
    {
        return $this->bookingFields()[$field] ?? $field;
    }

    protected function rules(): array
    {
        $item = $this->bookable();
        if (! $item) {
            return [];
        }
        $rules = [];
        foreach ($this->bookingType()->rules($item, fn (string $f) => $this->bookingProperty($f)) as $field => $rule) {
            $rules[$this->bookingProperty($field)] = $rule;
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        $names = [];
        foreach ($this->bookingType()->attributes() as $field => $label) {
            $names[$this->bookingProperty($field)] = $label;
        }

        return $names;
    }

    /** Current form values under their API names. */
    protected function bookingData(): array
    {
        $data = [];
        foreach ($this->bookingFields() as $field => $property) {
            $data[$field] = $this->{$property};
        }

        return $data;
    }

    /** Fill the form with the booking type's defaults, except the given API fields. */
    protected function applyBookingDefaults(array $except = []): void
    {
        foreach ($this->bookingType()->defaults($this->bookable()) as $field => $value) {
            if (! in_array($field, $except, true) && isset($this->bookingFields()[$field])) {
                $this->{$this->bookingFields()[$field]} = $value;
            }
        }
    }

    /** Total in UGX for the current input. */
    protected function bookingTotal(): float
    {
        $item = $this->bookable();

        return $item ? $this->bookingType()->quote($item, $this->bookingData()) : 0.0;
    }

    /** Sign-in check, validation, then the shared BookingService. */
    protected function placeBooking(string $message)
    {
        $customer = Customer::fromSession(app(CustomerAuth::class));
        if (! $customer) {
            $this->addError('clientemail', 'Please sign in to complete your booking.');

            return null;
        }

        $this->validate();
        $item = $this->bookable();
        abort_if(! $item, 404);

        app(BookingService::class)->book($this->bookingType(), $item, $this->bookingData(), $customer, app(Currency::class));

        session()->flash('alert', ['type' => 'success', 'message' => $message]);

        return $this->redirect('/BookingSuccess');
    }
}
