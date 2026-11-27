<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\Resource;
use App\Models\TaxiModel;
use App\Support\Vehicles;
use Illuminate\Database\Eloquent\Model;

/** Airport taxis (`airporttaxi`). The table has no approval column: every taxi is public. */
class TaxiResource extends Resource
{
    public static function model(): string { return TaxiModel::class; }
    public static function slug(): string { return 'airport-taxis'; }
    public static function label(): string { return 'Airport taxis'; }
    public static function singular(): string { return 'airport taxi'; }
    public static function icon(): string { return 'bi-taxi-front'; }
    public static function group(): string { return 'Vehicles'; }
    public static function titleColumn(): string { return 'TaxiNo'; }
    public static function media(): ?string { return 'taxi'; }
    public static function searchColumns(): array { return ['TaxiNo', 'CarSpec', 'CarCategory', 'DriverName', 'CurrentLocation', 'SearchTerms']; }
    public static function cacheKeys(): array { return ['vehicles.taxi.categories']; }

    public static function title(Model $record): string
    {
        return trim($record->CarSpec . ' · ' . $record->TaxiNo, ' ·');
    }

    public static function filters(): array
    {
        return [
            'CarCategory' => ['Category', fn () => array_combine($c = static::distinct('CarCategory'), $c)],
            'Availability' => ['Availability', ['Available' => 'Available', 'Unavailable' => 'Unavailable']],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('TaxiNo', 'Plate'),
            Column::make('CarSpec', 'Car'),
            Column::make('CarCategory', 'Category'),
            Column::make('DriverName', 'Driver'),
            Column::make('Price', 'From')->money(),
            Column::make('Availability', 'Availability')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('TaxiNo', 'Number plate')->required()->rules(['max:40', 'not_regex:/[\/\\\\]|\.\./'])
                ->help('Photos are named after the plate.')->span(4),
            Field::text('CarSpec', 'Make & model')->required()->span(4),
            Field::text('CarCategory', 'Category')->required()->suggest(fn () => static::distinct('CarCategory'))->span(4),
            Field::text('DriverName', 'Driver'),
            Field::email('EmailAddress', 'Email'),
            Field::text('CurrentLocation', 'Based at')->default('Entebbe Airport'),
            Field::money('Price', 'Base price')->help('UGX; trip prices come from Taxi trip prices'),
            Field::yesno('Automatic', 'Automatic')->default('Yes'),
            Field::number('Doors', 'Doors')->rules(['integer', 'min:0', 'max:10'])->span(3),
            Field::number('Passengers', 'Passengers')->rules(['integer', 'min:0', 'max:80'])->span(3),
            Field::number('Laggages', 'Bags')->rules(['integer', 'min:0', 'max:40'])->span(3),
            Flags::rating('Ratings', 'Rating'),
            Field::textarea('Description', 'Description'),
            Field::textarea('SearchTerms', 'Search keywords'),
            Flags::availability(),
            Flags::offered(),
        ];
    }

    public static function links(Model $record): array
    {
        return [['View on site', Vehicles::detailUrl('taxi', $record), 'bi-box-arrow-up-right']];
    }
}
