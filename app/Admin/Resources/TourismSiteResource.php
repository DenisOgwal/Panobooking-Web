<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\TourismSite;
use Illuminate\Database\Eloquent\Model;

/** Tourism sites (`tourismsites`). Photos are named after the site. */
class TourismSiteResource extends Resource
{
    public static function model(): string { return TourismSite::class; }
    public static function label(): string { return 'Tourism sites'; }
    public static function singular(): string { return 'tourism site'; }
    public static function icon(): string { return 'bi-tree'; }
    public static function group(): string { return 'Experiences & content'; }
    public static function titleColumn(): string { return 'SiteName'; }
    public static function media(): ?string { return 'site'; }
    public static function searchColumns(): array { return TourismSite::SEARCH_COLUMNS; }

    public static function filters(): array
    {
        return ['City' => ['City', fn () => array_combine($c = static::distinct('City'), $c)]];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('SiteName', 'Site'),
            Column::make('City', 'City'),
            Column::make('Country', 'Country'),
            Column::make('ShortDescription', 'Summary')->limit(70),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('SiteName', 'Site name')->required()->rules(['not_regex:/[\/\\\\]|\.\./']),
            Field::text('City', 'City')->required()->span(3)->suggest(fn () => static::distinct('City')),
            Field::text('Country', 'Country')->default('Uganda')->span(3),
            Field::textarea('ShortDescription', 'Summary')->rules(['max:500']),
            Field::textarea('Description', 'Full description'),
            Field::textarea('SearchKeyWords', 'Search keywords'),
        ];
    }

    public static function links(Model $record): array
    {
        return [['View on site', route('TourismSiteDetails', ['searchslug' => $record->getKey()]), 'bi-box-arrow-up-right']];
    }
}
