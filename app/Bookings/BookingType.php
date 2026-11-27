<?php

namespace App\Bookings;

use App\Support\Currency;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * One kind of booking (room, home stay, car, taxi, ambulance, tour): what is bookable, which
 * fields it takes, how the price is worked out, what goes in the cart and who is notified.
 *
 * Field names are the API's (check_in, rooms…). rules() receives a $field closure that turns
 * such a name into the caller's own name, so the Livewire pages can reuse the exact rules with
 * their historical property names (`after:{$field('check_in')}`).
 */
abstract class BookingType
{
    /** URL key, e.g. "room". */
    abstract public static function key(): string;

    /** Bookable item by id, or null when it doesn't exist (or isn't public). */
    abstract public function find(int|string|null $id): ?Model;

    /** @return array<string, array|string> keyed by API field name */
    abstract public function rules(Model $item, Closure $field): array;

    /** Human names for validation messages, keyed by API field name. */
    public function attributes(): array { return []; }

    /** Sensible starting values for the form. */
    abstract public function defaults(Model $item): array;

    /** Total in UGX for (possibly incomplete) input; never throws. */
    abstract public function quote(Model $item, array $data): float;

    /** Extra numbers shown with a quote (nights, days, rate…). */
    public function breakdown(Model $item, array $data): array { return []; }

    /** Cart columns besides User/UserNames/Cost/Currency/CurrencyAmount/OrderCode. */
    abstract public function cartAttributes(Model $item, array $data, Customer $customer): array;

    /** [script, params] for ProviderNotifier, or null to notify nobody. */
    public function notification(Model $item, array $data, Customer $customer, Currency $currency, float $total): ?array
    {
        return null;
    }

    // Helpers -----------------------------------------------------------

    protected static function nights(?string $from, ?string $to): int
    {
        try {
            return max(0, (int) Carbon::parse((string) $from)->diffInDays(Carbon::parse((string) $to), false));
        } catch (\Throwable) {
            return 0;
        }
    }

    protected static function int(array $data, string $key, int $default = 0): int
    {
        return max(0, (int) ($data[$key] ?? $default));
    }

    protected static function date(array $data, string $key): string
    {
        return Carbon::parse((string) $data[$key])->toDateString();
    }
}
