<?php

namespace App\Bookings\Types;

use App\Bookings\BookingType;
use App\Bookings\Customer;
use App\Models\TourPackages;
use App\Support\Currency;
use Closure;
use Illuminate\Database\Eloquent\Model;

/** A tour package: package fee × travellers, on the package's travel date. */
class TourBooking extends BookingType
{
    public static function key(): string { return 'tour'; }

    public function find(int|string|null $id): ?Model
    {
        return TourPackages::find($id);
    }

    public function rules(Model $item, Closure $field): array
    {
        return ['travellers' => 'required|integer|min:1|max:50'];
    }

    public function attributes(): array
    {
        return ['travellers' => 'travellers'];
    }

    public function defaults(Model $item): array
    {
        return ['travellers' => 1];
    }

    public function quote(Model $item, array $data): float
    {
        return (float) $item->PackageFees2 * self::int($data, 'travellers', 1);
    }

    public function breakdown(Model $item, array $data): array
    {
        return ['rate' => (float) $item->PackageFees2, 'unit' => 'per traveller'];
    }

    public function cartAttributes(Model $item, array $data, Customer $customer): array
    {
        $travellers = self::int($data, 'travellers', 1);

        return [
            'Facility' => $item->TravelCompany,
            'ProductName' => $item->PackageName,
            'Quantity' => $travellers,
            'DatesFrom' => $item->TravelDate ?: 'N/A',
            'DatesTo' => 'N/A',
            'BookingType' => 'Tour Package',
            'Descriptions' => 'Please Reserve ' . $item->PackageName . ' Package for ' . $customer->name . ' (' . $travellers . ' travellers)',
        ];
    }

    public function notification(Model $item, array $data, Customer $customer, Currency $currency, float $total): ?array
    {
        return ['SendPackage.php', [
            'packageemail' => $item->EmailAddress,
            'bookingnotes' => sprintf('Please reserve %s for %s, %d traveller(s). TOTAL: %s', $item->PackageName, $customer->name,
                self::int($data, 'travellers', 1), $currency->format($total)),
        ]];
    }
}
