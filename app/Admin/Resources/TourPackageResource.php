<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\TourPackages;
use Illuminate\Database\Eloquent\Model;

/** Tour packages (`tourpackage`). Photos are named {TravelCompany}{PackageName}. */
class TourPackageResource extends Resource
{
    public static function model(): string { return TourPackages::class; }
    public static function label(): string { return 'Tour packages'; }
    public static function singular(): string { return 'tour package'; }
    public static function icon(): string { return 'bi-compass'; }
    public static function group(): string { return 'Experiences & content'; }
    public static function titleColumn(): string { return 'PackageName'; }
    public static function media(): ?string { return 'tour'; }
    public static function searchColumns(): array { return [...TourPackages::SEARCH_COLUMNS, 'TravelCompany', 'DestinationsVisted']; }

    public static function filters(): array
    {
        return [
            'TravelCompany' => ['Company', fn () => array_combine($c = static::distinct('TravelCompany'), $c)],
            'City' => ['City', fn () => array_combine($c = static::distinct('City'), $c)],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('PackageName', 'Package'),
            Column::make('TravelCompany', 'Company'),
            Column::make('City', 'Starts in'),
            Column::make('TripDays', 'Days'),
            Column::make('TravelDate', 'Travel date')->date(),
            Column::make('PackageFees2', 'Fee')->money(),
        ];
    }

    public static function fields(): array
    {
        $safe = ['not_regex:/[\/\\\\]|\.\./'];

        return [
            Field::text('PackageName', 'Package name')->required()->rules($safe),
            Field::text('TravelCompany', 'Tour company')->required()->rules($safe)->suggest(fn () => static::distinct('TravelCompany'))
                ->help('Part of the photo file name ({company}{package}.jpg).'),
            Field::email('EmailAddress', 'Company email'),
            Field::text('City', 'Starts in')->suggest(fn () => static::distinct('City')),
            Field::money('PackageFees2', 'Package fee')->required(),
            Field::date('TravelDate', 'Travel date')->span(3),
            Field::number('TripDays', 'Days')->rules(['integer', 'min:0', 'max:365'])->span(3),
            Field::text('TravelHours', 'Departure time / hours'),
            Field::textarea('DestinationsVisted', 'Destinations visited'),
            Field::textarea('ServicesOffered', 'Included services'),
            Field::textarea('ExtraActivites', 'Extra activities'),
            Field::textarea('SearchTerms', 'Search keywords'),
        ];
    }

    public static function links(Model $record): array
    {
        return [['View on site', route('TourPackageDetails', ['searchslug' => $record->IDs]), 'bi-box-arrow-up-right']];
    }
}
