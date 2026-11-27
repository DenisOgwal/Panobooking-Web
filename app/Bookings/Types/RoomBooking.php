<?php

namespace App\Bookings\Types;

use App\Bookings\BookingType;
use App\Bookings\Customer;
use App\Models\RoomsModel;
use App\Support\Currency;
use Closure;
use Illuminate\Database\Eloquent\Model;

/** A hotel room: nights × rooms × room price. */
class RoomBooking extends BookingType
{
    public static function key(): string { return 'room'; }

    public function find(int|string|null $id): ?Model
    {
        return RoomsModel::with(['roomdetails', 'hotel'])->find($id);
    }

    public function rules(Model $item, Closure $field): array
    {
        $available = max(1, (int) $item->NoOfRooms);

        return [
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:' . $field('check_in'),
            'rooms' => "required|integer|min:1|max:{$available}",
            'children' => 'nullable|integer|min:0|max:20',
        ];
    }

    public function attributes(): array
    {
        return ['check_in' => 'check-in', 'check_out' => 'check-out', 'rooms' => 'rooms', 'children' => 'children'];
    }

    public function defaults(Model $item): array
    {
        return ['check_in' => now()->addDay()->toDateString(), 'check_out' => now()->addDays(2)->toDateString(), 'rooms' => 1, 'children' => 0];
    }

    public function quote(Model $item, array $data): float
    {
        return self::nights($data['check_in'] ?? null, $data['check_out'] ?? null) * self::int($data, 'rooms', 1) * (float) $item->Price;
    }

    public function breakdown(Model $item, array $data): array
    {
        return ['nights' => self::nights($data['check_in'] ?? null, $data['check_out'] ?? null), 'rate' => (float) $item->Price, 'unit' => 'per room per night'];
    }

    public function cartAttributes(Model $item, array $data, Customer $customer): array
    {
        return [
            'Facility' => $item->HotelName,
            'ProductName' => $item->RoomName,
            'Quantity' => self::int($data, 'rooms', 1),
            'DatesFrom' => self::date($data, 'check_in'),
            'DatesTo' => self::date($data, 'check_out'),
            'BookingType' => 'Stays',
            'Descriptions' => 'Has ' . self::int($data, 'children') . ' Children',
        ];
    }

    public function notification(Model $item, array $data, Customer $customer, Currency $currency, float $total): ?array
    {
        return ['SendHotel.php', [
            'hotelemail' => $item->hotel?->EmailAddress,
            'bookingnotes' => sprintf('Please reserve for %s %d %s room(s) from %s to %s. TOTAL PAYABLE: %s. Client has %d children.',
                $customer->name, self::int($data, 'rooms', 1), $item->RoomName, $data['check_in'], $data['check_out'],
                $currency->format($total), self::int($data, 'children')),
        ]];
    }
}
