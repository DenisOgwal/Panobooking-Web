<?php

namespace App\Bookings;

use App\Bookings\Types;
use App\Models\CartModel;
use App\Support\Currency;
use App\Support\ProviderNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Creates booking requests (pending `cart` rows) for the website and the mobile API.
 * Each kind of booking is described by a BookingType.
 */
class BookingService
{
    /** @return array<string, class-string<BookingType>> */
    public static function types(): array
    {
        return [
            Types\RoomBooking::key() => Types\RoomBooking::class,
            Types\HomeStayBooking::key() => Types\HomeStayBooking::class,
            Types\CarBooking::key() => Types\CarBooking::class,
            Types\TaxiBooking::key() => Types\TaxiBooking::class,
            Types\AmbulanceBooking::key() => Types\AmbulanceBooking::class,
            Types\TourBooking::key() => Types\TourBooking::class,
        ];
    }

    public function type(string $key): ?BookingType
    {
        $class = self::types()[$key] ?? null;

        return $class ? app($class) : null;
    }

    /** Validate API-named input for a booking; throws ValidationException. */
    public function validate(BookingType $type, Model $item, array $input): array
    {
        $rules = $type->rules($item, fn (string $name) => $name);

        return Validator::make($input, $rules, [], $type->attributes())->validate();
    }

    /** Price for the input (already validated, or partial for a live preview). */
    public function quote(BookingType $type, Model $item, array $data, Currency $currency): array
    {
        $total = $type->quote($item, $data);

        return ['total' => $total, 'currency' => $currency->code(), 'converted' => $currency->convert($total),
            'details' => $type->breakdown($item, $data)];
    }

    /** Validate, store the pending booking and notify the provider. */
    public function book(BookingType $type, Model $item, array $input, Customer $customer, Currency $currency): CartModel
    {
        $data = $this->validate($type, $item, $input);
        $total = $type->quote($item, $data);

        $booking = CartModel::create($type->cartAttributes($item, $data, $customer) + [
            'User' => $customer->email,
            'UserNames' => $customer->name,
            'Cost' => $total,
            'OrderCode' => 'N/A',
            'Currency' => $currency->code(),
            'CurrencyAmount' => $currency->convert($total),
        ]);

        if ($notification = $type->notification($item, $data, $customer, $currency, $total)) {
            app(ProviderNotifier::class)->send(...$notification);
        }

        return $booking->refresh();
    }
}
