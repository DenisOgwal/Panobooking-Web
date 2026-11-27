<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\Resource;
use App\Models\HotelsModel;
use App\Models\RoomDetailsModel;
use App\Models\RoomsModel;
use Illuminate\Database\Eloquent\Model;

/** Room types of a property (`rooms`, linked by `HotelName` = property `Place`). */
class RoomResource extends Resource
{
    public static function model(): string { return RoomsModel::class; }
    public static function label(): string { return 'Rooms'; }
    public static function singular(): string { return 'room'; }
    public static function icon(): string { return 'bi-door-open'; }
    public static function group(): string { return 'Stays'; }
    public static function titleColumn(): string { return 'RoomName'; }
    public static function media(): ?string { return 'room'; }
    public static function searchColumns(): array { return ['RoomName', 'HotelName', 'RoomDesc']; }
    public static function defaultSort(): array { return ['HotelName', 'asc']; }

    public static function title(Model $record): string
    {
        return $record->RoomName . ' · ' . $record->HotelName;
    }

    private static function properties(): array
    {
        $names = HotelsModel::orderBy('Place')->pluck('Place')->filter()->all();

        return array_combine($names, $names);
    }

    public static function filters(): array
    {
        return [
            'HotelName' => ['Property', fn () => self::properties()],
            'Availability' => ['Availability', ['Available' => 'Available', 'Unavailable' => 'Unavailable']],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('RoomName', 'Room'),
            Column::make('HotelName', 'Property'),
            Column::make('Price', 'Price')->money(),
            Column::make('NoOfRooms', 'Rooms'),
            Column::make('Availability', 'Availability')->badge(),
            Column::make('Offered', 'Offer')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::select('HotelName', 'Property', fn () => self::properties())->required(),
            Field::text('RoomName', 'Room name')->required()->rules(['not_regex:/[\/\\\\]|\.\./']),
            Field::money('Price', 'Price per night')->required(),
            Field::money('Cost', 'Cost (internal)')->help('UGX; not shown on the site'),
            Field::number('NoOfRooms', 'Number of rooms')->rules(['integer', 'min:0'])->span(3),
            Field::yesno('BreakFast', 'Breakfast included')->default('Yes'),
            Flags::availability(),
            Flags::offered(),
            Field::textarea('RoomDesc', 'Description'),
            Field::textarea('RefundPolicy', 'Refund policy'),
        ];
    }

    public static function checklist(): ?array
    {
        return [
            'label' => 'Room features',
            'model' => RoomDetailsModel::class,
            'match' => fn (array $a) => ['roomid' => $a['IDs'] ?? null],
            'extra' => fn (array $a) => ['RoomName' => $a['RoomName'] ?? null],
            'text' => ['Feet' => 'Room size'],
        ];
    }

    public static function links(Model $record): array
    {
        $hotel = HotelsModel::where('Place', $record->HotelName)->value('IDs');

        return $hotel ? [['Property', route('admin.resources.edit', ['resource' => 'properties', 'id' => $hotel]), 'bi-building']] : [];
    }
}
