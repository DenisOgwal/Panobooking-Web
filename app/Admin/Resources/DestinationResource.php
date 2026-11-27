<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\DestinationsModel;

/** Cities and regions offered in search, the home page mosaic and the destinations pages (`destinations`). */
class DestinationResource extends Resource
{
    public static function model(): string { return DestinationsModel::class; }
    public static function label(): string { return 'Destinations'; }
    public static function singular(): string { return 'destination'; }
    public static function icon(): string { return 'bi-geo-alt'; }
    public static function group(): string { return 'Stays'; }
    public static function titleColumn(): string { return 'City'; }
    /** One photo per city ({City}.jpg), shown on the home page mosaic and the Cities page. */
    public static function media(): ?string { return 'city'; }
    public static function searchColumns(): array { return ['City', 'Region', 'Country']; }
    public static function defaultSort(): array { return ['City', 'asc']; }

    public static function filters(): array
    {
        return [
            'Region' => ['Region', fn () => array_combine($r = static::distinct('Region'), $r)],
            'Country' => ['Country', fn () => array_combine($c = static::distinct('Country'), $c)],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('City', 'City'),
            Column::make('Region', 'Region'),
            Column::make('Country', 'Country'),
            Column::make('DestinationID', 'Order'),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('City', 'City')->required()->rules(['not_regex:/[\/\\\\]|\.\./'])->span(4)
                ->help('The photo is stored as {City}.jpg; renaming the city moves it.'),
            Field::text('Region', 'Region')->required()->suggest(fn () => static::distinct('Region'))->span(4),
            Field::text('Country', 'Country')->required()->default('Uganda')->span(4),
            Field::number('DestinationID', 'Display order')->rules(['integer', 'min:0'])->span(4),
        ];
    }
}
