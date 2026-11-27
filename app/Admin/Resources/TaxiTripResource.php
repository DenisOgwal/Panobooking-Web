<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\TaxiTripModel;
use Illuminate\Database\Eloquent\Model;

/** Airport taxi fares from an airport to a destination (`taxitrip`), used by the taxi booking form. */
class TaxiTripResource extends Resource
{
    public static function model(): string { return TaxiTripModel::class; }
    public static function slug(): string { return 'taxi-trips'; }
    public static function label(): string { return 'Taxi trip prices'; }
    public static function singular(): string { return 'taxi trip price'; }
    public static function icon(): string { return 'bi-signpost-split'; }
    public static function group(): string { return 'Vehicles'; }
    public static function titleColumn(): string { return 'Tos'; }
    public static function searchColumns(): array { return ['Froms', 'Tos']; }
    public static function defaultSort(): array { return ['Tos', 'asc']; }

    public static function title(Model $record): string
    {
        return $record->Froms . ' → ' . $record->Tos;
    }

    public static function filters(): array
    {
        return ['Froms' => ['From', fn () => array_combine($f = static::distinct('Froms'), $f)]];
    }

    public static function columns(): array
    {
        return [
            Column::make('Froms', 'From'),
            Column::make('Tos', 'To'),
            Column::make('Price', 'Fare')->money(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('Froms', 'From (airport)')->required()->suggest(fn () => static::distinct('Froms'))->default('Entebbe International Airport'),
            Field::text('Tos', 'To')->required(),
            Field::money('Price', 'Fare')->required(),
        ];
    }
}
