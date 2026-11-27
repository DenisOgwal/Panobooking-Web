<?php

namespace App\Bookings\Types;

use App\Bookings\Customer;
use App\Models\TaxiTripModel;
use App\Support\Currency;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/** An airport transfer at the fixed fare for the airport and destination (`taxitrip`). */
class TaxiBooking extends VehicleBookingType
{
    public const TRIPS = ['From Airport', 'To Airport'];

    public static function key(): string { return 'taxi'; }

    protected function vehicleType(): string { return 'taxi'; }

    /** Destinations served from an airport. */
    public static function destinations(?string $airport): Collection
    {
        return TaxiTripModel::where('Froms', 'like', addcslashes((string) $airport, '%_\\') . '%')
            ->orderBy('Tos')->pluck('Tos')->unique()->values();
    }

    /** Fixed fare (UGX) for a route, or null when the route isn't served. */
    public static function fare(?string $airport, ?string $destination): ?float
    {
        $price = TaxiTripModel::where('Froms', 'like', addcslashes((string) $airport, '%_\\') . '%')->where('Tos', $destination)->value('Price');

        return $price !== null ? (float) $price : null;
    }

    public function rules(Model $item, Closure $field): array
    {
        return [
            'trip' => 'required|in:' . implode(',', self::TRIPS),
            'airport' => 'required|string|max:120',
            'destination' => 'required|string|max:200',
            'passengers' => 'required|integer|min:1|max:14',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return ['trip' => 'trip', 'airport' => 'airport', 'destination' => 'destination', 'passengers' => 'passengers',
            'date' => 'date', 'time' => 'time', 'notes' => 'notes'];
    }

    public function defaults(Model $item): array
    {
        return ['trip' => 'From Airport', 'airport' => 'Entebbe', 'destination' => (string) self::destinations('Entebbe')->first(),
            'passengers' => 1, 'date' => now()->addDay()->toDateString(), 'time' => '10:00', 'notes' => ''];
    }

    public function quote(Model $item, array $data): float
    {
        return self::fare($data['airport'] ?? null, $data['destination'] ?? null) ?? 0.0;
    }

    public function breakdown(Model $item, array $data): array
    {
        return ['fare_known' => self::fare($data['airport'] ?? null, $data['destination'] ?? null) !== null, 'unit' => 'per trip'];
    }

    public static function routeDescription(array $data): string
    {
        return ($data['trip'] ?? 'From Airport') === 'From Airport'
            ? "Pick Me up from {$data['airport']} Airport to {$data['destination']}"
            : "Pick Me up from {$data['destination']} to {$data['airport']} Airport";
    }

    public function cartAttributes(Model $item, array $data, Customer $customer): array
    {
        return $this->vehicleCart($item) + [
            'Quantity' => 1,
            'DatesFrom' => Carbon::parse((string) $data['date'])->toDateString() . ' ' . $data['time'],
            'DatesTo' => 'N/A',
            'BookingType' => 'Airport Taxi',
            'Descriptions' => self::routeDescription($data) . '. NO. OF PEOPLE: ' . self::int($data, 'passengers', 1) . '. NOTES: ' . ($data['notes'] ?? ''),
        ];
    }

    public function notification(Model $item, array $data, Customer $customer, Currency $currency, float $total): ?array
    {
        return ['SendTaxi.php', [
            'hotelemail' => $item->EmailAddress,
            'bookingnotes' => sprintf('%s for %s on %s at %s. Taxi %s. People: %d. FARE: %s. NOTES: %s',
                self::routeDescription($data), $customer->name, $data['date'], $data['time'],
                $item->TaxiNo, self::int($data, 'passengers', 1), $currency->format($total), (string) ($data['notes'] ?? '')),
        ]];
    }
}
