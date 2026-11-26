<?php

namespace App\Support;

use App\Models\AmbulanceModel;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * One place describing the three vehicle verticals (rental cars, airport taxis, ambulances):
 * which model, which category column, which routes, and how they're labelled.
 */
class Vehicles
{
    public const TYPES = [
        'car' => [
            'model' => RentalCarModel::class,
            'category' => 'CarCategory',
            'label' => 'Rental car', 'plural' => 'Rental cars', 'icon' => 'bi-car-front',
            'landing' => '/cars', 'searchTab' => 'cars',
            'list' => ['CarsList', 'carcategoriesslug'],
            'detail' => ['CarDetails', 'carslug'],
            'book' => ['CompleteCarBooking', 'carrentalfinalslug'],
            'unit' => 'per day', 'approved' => true,
        ],
        'taxi' => [
            'model' => TaxiModel::class,
            'category' => 'CarCategory',
            'label' => 'Airport taxi', 'plural' => 'Airport taxis', 'icon' => 'bi-taxi-front',
            'landing' => '/taxis', 'searchTab' => 'taxi',
            'list' => ['TaxisList', 'taxicategoriesslug'],
            'detail' => ['TaxiDetails', 'taxislug'],
            'book' => ['CompleteTaxiBooking', 'taxifinalslug'],
            'unit' => 'per trip', 'approved' => false, // the taxi table has no approval column
        ],
        'ambulance' => [
            'model' => AmbulanceModel::class,
            'category' => 'AmbulanceCategory',
            'label' => 'Ambulance', 'plural' => 'Ambulances', 'icon' => 'bi-heart-pulse',
            'landing' => '/ambulance', 'searchTab' => 'ambulance',
            'list' => ['AmbulanceList', 'ambulancecategoriesslug'],
            'detail' => ['AmbulanceDetails', 'ambulanceslug'],
            'book' => ['CompleteAmbulanceBooking', 'ambulancefinalslug'],
            'unit' => null, 'approved' => true,
        ],
    ];

    public static function config(string $type): array
    {
        return self::TYPES[$type];
    }

    /** Base query: approved where the table supports it. */
    public static function query(string $type): Builder
    {
        $c = self::config($type);
        $query = $c['model']::query();

        return $c['approved'] ? $query->approved() : $query;
    }

    /** @return Collection<string, int> category => count (cached) */
    public static function categories(string $type): Collection
    {
        $column = self::config($type)['category'];

        return Cache::remember("vehicles.{$type}.categories", 300, fn () => self::query($type)
            ->whereNotNull($column)->selectRaw("{$column} as name, COUNT(*) as total")
            ->groupBy($column)->orderBy($column)->pluck('total', 'name')->map(fn ($n) => (int) $n));
    }

    public static function listUrl(string $type, ?string $category = null): string
    {
        [$route, $param] = self::config($type)['list'];

        return $category ? route($route, [$param => $category]) : route($route);
    }

    public static function detailUrl(string $type, $vehicle): string
    {
        [$route, $param] = self::config($type)['detail'];

        return route($route, [$param => $vehicle->IDs]);
    }

    public static function bookUrl(string $type, $vehicle): string
    {
        [$route, $param] = self::config($type)['book'];

        return route($route, [$param => $vehicle->IDs]);
    }
}
