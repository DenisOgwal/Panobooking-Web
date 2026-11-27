<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\events;
use Illuminate\Database\Eloquent\Model;

/** Events (`events`). Photos are named after the event. */
class EventResource extends Resource
{
    public static function model(): string { return events::class; }
    public static function label(): string { return 'Events'; }
    public static function singular(): string { return 'event'; }
    public static function icon(): string { return 'bi-calendar-event'; }
    public static function group(): string { return 'Experiences & content'; }
    public static function titleColumn(): string { return 'EventName'; }
    public static function media(): ?string { return 'event'; }
    public static function searchColumns(): array { return events::SEARCH_COLUMNS; }
    public static function defaultSort(): array { return ['StartDate', 'desc']; }

    public static function filters(): array
    {
        return ['City' => ['City', fn () => array_combine($c = static::distinct('City'), $c)]];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('EventName', 'Event'),
            Column::make('City', 'City'),
            Column::make('StartDate', 'Starts')->date(),
            Column::make('EndDate', 'Ends')->date(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('EventName', 'Event name')->required()->rules(['not_regex:/[\/\\\\]|\.\./']),
            Field::text('City', 'City')->required()->span(3)->suggest(fn () => static::distinct('City')),
            Field::text('Country', 'Country')->default('Uganda')->span(3),
            Field::date('StartDate', 'Starts')->required(),
            Field::date('EndDate', 'Ends')->required()->rules(['after_or_equal:data.StartDate']),
            Field::textarea('ShortDescription', 'Summary')->rules(['max:500']),
            Field::textarea('Description', 'Full description'),
            Field::textarea('SearchKeyWords', 'Search keywords'),
        ];
    }

    public static function links(Model $record): array
    {
        return [['View on site', route('EventsDetails', ['searchslug' => $record->getKey()]), 'bi-box-arrow-up-right']];
    }
}
