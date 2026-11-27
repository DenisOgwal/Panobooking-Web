<?php

namespace App\Bookings\Types;

use App\Bookings\BookingType;
use App\Bookings\Customer;
use App\Models\HomeGuestModel;
use Closure;
use Illuminate\Database\Eloquent\Model;

/** A whole home stay: nights × nightly price, with a note for the host. */
class HomeStayBooking extends BookingType
{
    public static function key(): string { return 'home-stay'; }

    public function find(int|string|null $id): ?Model
    {
        return HomeGuestModel::with('houserulesdetail')->find($id);
    }

    public function rules(Model $item, Closure $field): array
    {
        return [
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:' . $field('check_in'),
            'children' => 'nullable|integer|min:0|max:20',
            'notes' => 'required|string|min:3|max:1000',
        ];
    }

    public function attributes(): array
    {
        return ['check_in' => 'check-in', 'check_out' => 'check-out', 'children' => 'children', 'notes' => 'notes for your host'];
    }

    public function defaults(Model $item): array
    {
        return ['check_in' => now()->addDay()->toDateString(), 'check_out' => now()->addDays(2)->toDateString(), 'children' => 0, 'notes' => ''];
    }

    public function quote(Model $item, array $data): float
    {
        return self::nights($data['check_in'] ?? null, $data['check_out'] ?? null) * (float) $item->Prices;
    }

    public function breakdown(Model $item, array $data): array
    {
        return ['nights' => self::nights($data['check_in'] ?? null, $data['check_out'] ?? null), 'rate' => (float) $item->Prices, 'unit' => 'per night'];
    }

    public function cartAttributes(Model $item, array $data, Customer $customer): array
    {
        return [
            'Facility' => $item->GuestHouseName,
            'ProductName' => $item->GuestHouseName,
            'Quantity' => 1,
            'DatesFrom' => self::date($data, 'check_in'),
            'DatesTo' => self::date($data, 'check_out'),
            'BookingType' => 'Home Guest House',
            'Descriptions' => 'Has ' . self::int($data, 'children') . ' Children, ' . $data['notes'],
        ];
    }
}
