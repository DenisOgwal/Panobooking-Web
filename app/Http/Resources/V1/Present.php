<?php

namespace App\Http\Resources\V1;

use App\Admin\PropertyDetailSections;
use App\Support\Currency;
use App\Support\Features;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/** Small formatting helpers shared by the API resources. */
final class Present
{
    /** A UGX amount with its conversion into the request's currency. */
    public static function price(float|int|string|null $ugx): ?array
    {
        if ($ugx === null || $ugx === '') {
            return null;
        }
        $currency = app(Currency::class);

        return [
            'ugx' => (float) $ugx,
            'amount' => $currency->convert($ugx),
            'currency' => $currency->code(),
            'formatted' => $currency->format($ugx),
        ];
    }

    public static function photo(string $type, Model $model): string
    {
        return Media::image($type, $model);
    }

    /** @return list<string> */
    public static function gallery(string $type, Model $model, int $max = 9): array
    {
        return Media::gallery($type, $model, $max);
    }

    public static function yes($value): bool
    {
        return Features::isOn($value);
    }

    /** Yes/No feature row as [{label, available}] for the columns that are on or off. */
    public static function features(?Model $row): array
    {
        $out = [];
        foreach (Features::fromModel($row) as $label => $value) {
            if (in_array(strtolower(trim((string) $value)), ['yes', 'no'], true)) {
                $out[] = ['label' => $label, 'available' => Features::isOn($value)];
            }
        }

        return $out;
    }

    public static function float($value): ?float
    {
        return $value === null || $value === '' ? null : (float) $value;
    }

    public static function int($value): ?int
    {
        return $value === null || $value === '' || ! is_numeric($value) ? null : (int) $value;
    }

    public static function date($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return rescue(fn () => Carbon::parse($value)->toDateString(), null, false);
    }

    /** "0.3476,32.5825" => {lat, lng} */
    public static function coordinates(?string $value): ?array
    {
        if (! $value || ! preg_match('/^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)\s*$/', $value, $m)) {
            return null;
        }

        return ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
    }

    /**
     * Amenities, policies and surroundings of a stay, grouped like the website:
     * {key: {label, tab, items: [...]}} where items are labels, {label, available} or row objects.
     */
    public static function propertyDetails(string $name): array
    {
        $out = [];
        foreach (array_keys(PropertyDetailSections::all()) as $key) {
            $s = PropertyDetailSections::get($key);
            $rows = $s['model']::query()->where('PropertyName', $name)->orderBy('id')->get();

            $items = match ($s['kind']) {
                'flags' => self::features($rows->first()),
                'form' => $rows->first() ? collect($s['fields'])->mapWithKeys(fn ($label, $col) => [self::snake($col) => $rows->first()->{$col}])
                    ->filter(fn ($v) => filled($v))->all() : [],
                default => array_keys($s['fields']) === ['Amenity']
                    ? $rows->pluck('Amenity')->filter()->values()->all()
                    : $rows->map(fn ($r) => collect($s['fields'])->mapWithKeys(fn ($label, $col) => [self::snake($col) => $r->{$col}])->all())->all(),
            };
            if ($items !== []) {
                $out[$key] = ['label' => $s['label'], 'tab' => $s['tab'], 'items' => $items];
            }
        }

        return $out;
    }

    private static function snake(string $column): string
    {
        return \Illuminate\Support\Str::snake($column);
    }
}
