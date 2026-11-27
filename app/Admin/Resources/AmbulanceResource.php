<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\Resource;
use App\Models\AmbulanceAccessoriesModel;
use App\Models\AmbulanceModel;
use App\Support\Vehicles;
use Illuminate\Database\Eloquent\Model;

/** Ambulances (`ambulances`). Photos are named after the plate; equipment lives in `ambulanceaccessories`. */
class AmbulanceResource extends Resource
{
    public static function model(): string { return AmbulanceModel::class; }
    public static function label(): string { return 'Ambulances'; }
    public static function singular(): string { return 'ambulance'; }
    public static function icon(): string { return 'bi-heart-pulse'; }
    public static function group(): string { return 'Vehicles'; }
    public static function titleColumn(): string { return 'TaxiNo'; }
    public static function media(): ?string { return 'ambulance'; }
    public static function approval(): ?array { return Flags::approval(); }
    public static function searchColumns(): array { return ['TaxiNo', 'AmbulanceCategory', 'DriverName', 'City', 'CurrentLocation', 'SearchTerms', 'TelephoneNumber']; }
    public static function cacheKeys(): array { return ['vehicles.ambulance.categories']; }

    public static function title(Model $record): string
    {
        return trim($record->AmbulanceCategory . ' · ' . $record->TaxiNo, ' ·');
    }

    public static function filters(): array
    {
        return [
            ...Flags::approvalFilter(),
            'AmbulanceCategory' => ['Category', fn () => array_combine($c = static::distinct('AmbulanceCategory'), $c)],
            'Availability' => ['Availability', ['Available' => 'Available', 'Unavailable' => 'Unavailable']],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('TaxiNo', 'Plate'),
            Column::make('AmbulanceCategory', 'Category'),
            Column::make('DriverName', 'Operator'),
            Column::make('City', 'City'),
            Column::make('Price', 'Price')->money(),
            Column::make('Availability', 'Availability')->badge(),
            Column::make('Approved', 'Approval')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('TaxiNo', 'Number plate')->required()->rules(['max:40', 'not_regex:/[\/\\\\]|\.\./'])
                ->help('Photos and equipment are linked by the plate.')->span(4),
            Field::text('AmbulanceCategory', 'Category')->required()->suggest(fn () => static::distinct('AmbulanceCategory'))->span(4),
            Field::text('DriverName', 'Operator / driver')->required()->span(4),
            Field::text('TelephoneNumber', 'Phone'),
            Field::email('EmailAddress', 'Email'),
            Field::text('City', 'City')->suggest(fn () => static::distinct('City')),
            Field::text('CurrentLocation', 'Current location'),
            Field::money('Price', 'Price')->help('UGX; 0 if charged on request'),
            Flags::rating('Ratings', 'Rating'),
            Field::text('Repayments', 'Payment method')->rules(['max:60'])->suggest(['Cash', 'Mobile Money', 'Card', 'Bank transfer'])->span(3),
            Field::textarea('CarSpec', 'Description'),
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
            'label' => 'Equipment on board',
            'model' => AmbulanceAccessoriesModel::class,
            'match' => fn (array $a) => ['Ambulance' => $a['TaxiNo'] ?? null],
            'text' => ['MiscelaniousItems' => 'Other equipment'],
        ];
    }

    public static function links(Model $record): array
    {
        return [['View on site', Vehicles::detailUrl('ambulance', $record), 'bi-box-arrow-up-right']];
    }
}
