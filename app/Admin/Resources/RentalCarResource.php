<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\Resource;
use App\Models\CarIncludes;
use App\Models\RentalCarModel;
use App\Support\Vehicles;
use Illuminate\Database\Eloquent\Model;

/** Rental cars (`carrentals`). Photos are named {DriverName}{TaxiNo}; extras live in `carincludes`. */
class RentalCarResource extends Resource
{
    public static function model(): string { return RentalCarModel::class; }
    public static function label(): string { return 'Rental cars'; }
    public static function singular(): string { return 'rental car'; }
    public static function icon(): string { return 'bi-car-front'; }
    public static function group(): string { return 'Vehicles'; }
    public static function titleColumn(): string { return 'TaxiNo'; }
    public static function media(): ?string { return 'car'; }
    public static function approval(): ?array { return Flags::approval(); }
    public static function searchColumns(): array { return [...RentalCarModel::SEARCH_COLUMNS, 'EmailAddress', 'TelephoneNumber']; }
    public static function cacheKeys(): array { return ['vehicles.car.categories']; }

    public static function title(Model $record): string
    {
        return trim($record->CarSpec . ' · ' . $record->TaxiNo, ' ·');
    }

    public static function filters(): array
    {
        return [
            ...Flags::approvalFilter(),
            'CarCategory' => ['Category', fn () => array_combine($c = static::distinct('CarCategory'), $c)],
            'Company' => ['Company', fn () => array_combine($c = static::distinct('Company'), $c)],
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
            Column::make('Company', 'Company'),
            Column::make('Price', 'Per day')->money(),
            Column::make('Availability', 'Availability')->badge(),
            Column::make('Approved', 'Approval')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('TaxiNo', 'Number plate')->required()->rules(['max:40', 'not_regex:/[\/\\\\]|\.\./'])->span(4),
            Field::text('CarSpec', 'Make & model')->required()->span(4),
            Field::text('CarCategory', 'Category')->required()->suggest(fn () => static::distinct('CarCategory'))->span(4),
            Field::text('Company', 'Company')->required()->suggest(fn () => static::distinct('Company')),
            Field::text('DriverName', 'Owner / driver')->required()->rules(['not_regex:/[\/\\\\]|\.\./'])
                ->help('Part of the photo file name ({owner}{plate}.jpg).'),
            Field::text('TelephoneNumber', 'Phone'),
            Field::email('EmailAddress', 'Email'),
            Field::text('City', 'City')->suggest(fn () => static::distinct('City')),
            Field::text('CurrentLocation', 'Current location'),
            Field::money('Price', 'Price per day (Kampala)')->required(),
            Field::money('PricePerDay', 'Price per day (upcountry)'),
            Field::yesno('Automatic', 'Automatic')->default('Yes'),
            Field::number('Doors', 'Doors')->rules(['integer', 'min:0', 'max:10'])->span(3),
            Field::number('Passengers', 'Passengers')->rules(['integer', 'min:0', 'max:80'])->span(3),
            Field::number('Laggages', 'Bags')->rules(['integer', 'min:0', 'max:40'])->span(3),
            Field::number('DriverAge', 'Minimum driver age')->rules(['integer', 'min:16', 'max:99'])->span(3),
            Flags::rating('Ratings', 'Rating'),
            Field::text('Repayments', 'Payment method')->rules(['max:60'])->suggest(['Cash', 'Mobile Money', 'Card', 'Bank transfer'])->span(3),
            Field::textarea('Description', 'Description'),
            Field::textarea('SearchTerms', 'Search keywords'),
            Flags::availability(),
            Flags::offered(),
            Flags::approved(),
            Field::password('Password', 'Driver app password', fn (string $plain) => md5($plain))
                ->help('Used by the driver app (stored as MD5, which that app requires). Leave blank to keep the current one.'),
        ];
    }

    public static function checklist(): ?array
    {
        return [
            'label' => 'Car features',
            'model' => CarIncludes::class,
            'match' => fn (array $a) => ['Car' => $a['TaxiNo'] ?? null, 'Company' => $a['DriverName'] ?? null],
        ];
    }

    public static function links(Model $record): array
    {
        return [['View on site', Vehicles::detailUrl('car', $record), 'bi-box-arrow-up-right']];
    }
}
