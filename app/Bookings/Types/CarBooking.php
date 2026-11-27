<?php

namespace App\Bookings\Types;

use App\Bookings\Customer;
use App\Support\Currency;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/** A rental car: daily rate (Kampala or upcountry) × cars × days. */
class CarBooking extends VehicleBookingType
{
    public const AREAS = ['Kampala', 'Upcountry'];

    public static function key(): string { return 'car'; }

    protected function vehicleType(): string { return 'car'; }

    public function rules(Model $item, Closure $field): array
    {
        return [
            'pickup_date' => 'required|date|after_or_equal:today',
            'days' => 'required|integer|min:1|max:90',
            'cars' => 'required|integer|min:1|max:15',
            'driving_area' => 'required|in:' . implode(',', self::AREAS),
            'pickup_point' => 'required|string|min:2|max:200',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return ['pickup_date' => 'pick-up date', 'days' => 'days', 'cars' => 'cars', 'driving_area' => 'driving area',
            'pickup_point' => 'pick-up point', 'notes' => 'notes'];
    }

    public function defaults(Model $item): array
    {
        return ['pickup_date' => now()->addDay()->toDateString(), 'days' => 1, 'cars' => 1, 'driving_area' => 'Kampala',
            'pickup_point' => (string) ($item->CurrentLocation ?: $item->City), 'notes' => ''];
    }

    /** Daily rate for the chosen driving area (upcountry uses PricePerDay when set). */
    public function rate(Model $item, ?string $area): float
    {
        return (float) ($area === 'Upcountry' && $item->PricePerDay ? $item->PricePerDay : $item->Price);
    }

    public function quote(Model $item, array $data): float
    {
        return $this->rate($item, $data['driving_area'] ?? null) * self::int($data, 'cars', 1) * self::int($data, 'days', 1);
    }

    public function breakdown(Model $item, array $data): array
    {
        return ['days' => self::int($data, 'days', 1), 'rate' => $this->rate($item, $data['driving_area'] ?? null), 'unit' => 'per car per day'];
    }

    public function cartAttributes(Model $item, array $data, Customer $customer): array
    {
        $from = Carbon::parse((string) $data['pickup_date']);
        $notes = (string) ($data['notes'] ?? '');

        return $this->vehicleCart($item) + [
            'Quantity' => self::int($data, 'cars', 1),
            'DatesFrom' => $from->toDateString(),
            'DatesTo' => $from->copy()->addDays(self::int($data, 'days', 1))->toDateString(),
            'BookingType' => 'Car Rental',
            'Descriptions' => trim("I will Pick the Car at {$data['pickup_point']}. DRIVING: {$data['driving_area']}. NOTES: {$notes}"),
        ];
    }

    public function notification(Model $item, array $data, Customer $customer, Currency $currency, float $total): ?array
    {
        return ['SendCar.php', [
            'hotelemail' => $item->EmailAddress,
            'bookingnotes' => sprintf('Please reserve for %s %d x %s for %d day(s) from %s (%s driving). Pick-up: %s. TOTAL PAYABLE: %s. NOTES: %s',
                $customer->name, self::int($data, 'cars', 1), $item->TaxiNo, self::int($data, 'days', 1),
                $data['pickup_date'], $data['driving_area'], $data['pickup_point'], $currency->format($total), (string) ($data['notes'] ?? '')),
        ]];
    }
}
