<?php

namespace App\Bookings\Types;

use App\Bookings\Customer;
use App\Support\Currency;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/** An ambulance pick-up request. No price: the operator confirms the charge. */
class AmbulanceBooking extends VehicleBookingType
{
    public static function key(): string { return 'ambulance'; }

    protected function vehicleType(): string { return 'ambulance'; }

    public function rules(Model $item, Closure $field): array
    {
        return [
            'area' => 'required|string|min:2|max:150',
            'pickup_point' => 'required|string|min:2|max:200',
            'reason' => 'required|string|min:10|max:1000',
            'notes' => 'nullable|string|max:1000',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
        ];
    }

    public function attributes(): array
    {
        return ['area' => 'area', 'pickup_point' => 'exact pick-up point', 'reason' => 'reason', 'notes' => 'notes', 'date' => 'date', 'time' => 'time'];
    }

    public function defaults(Model $item): array
    {
        return ['area' => (string) ($item->CurrentLocation ?: $item->City), 'pickup_point' => '', 'reason' => '', 'notes' => '',
            'date' => now()->toDateString(), 'time' => now()->addMinutes(30)->format('H:i')];
    }

    public function quote(Model $item, array $data): float
    {
        return 0.0;
    }

    public function cartAttributes(Model $item, array $data, Customer $customer): array
    {
        $notes = (string) ($data['notes'] ?? '');

        return $this->vehicleCart($item) + [
            'Quantity' => 1,
            'DatesFrom' => Carbon::parse((string) $data['date'])->toDateString() . ' ' . $data['time'],
            'DatesTo' => 'N/A',
            'BookingType' => 'Ambulance',
            'Descriptions' => "Please Pick me up from {$data['area']}. EXACT PICKUPPOINT: {$data['pickup_point']}. REASON: {$data['reason']}. NOTES: {$notes}",
        ];
    }

    public function notification(Model $item, array $data, Customer $customer, Currency $currency, float $total): ?array
    {
        return ['SendAmbulance.php', [
            'hotelemail' => $item->EmailAddress,
            'bookingnotes' => sprintf('Please help %s. Ambulance %s. Pick-up %s at %s from %s (exact point: %s). REASON: %s. NOTES: %s',
                $customer->name, $item->TaxiNo, $data['date'], $data['time'], $data['area'], $data['pickup_point'],
                $data['reason'], (string) ($data['notes'] ?? '')),
        ]];
    }
}
