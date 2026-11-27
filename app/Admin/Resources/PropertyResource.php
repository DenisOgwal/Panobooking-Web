<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\MediaFiles;
use App\Admin\PropertyDetailSections;
use App\Admin\Resource;
use App\Livewire\RegisterProperty;
use App\Models\HotelsModel;
use App\Models\RoomDetailsModel;
use App\Models\RoomsModel;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/** Hotels, lodges, apartments… (`pickuplocations`, named by `Place`). */
class PropertyResource extends Resource
{
    public static function model(): string { return HotelsModel::class; }
    public static function label(): string { return 'Properties'; }
    public static function singular(): string { return 'property'; }
    public static function icon(): string { return 'bi-building'; }
    public static function group(): string { return 'Stays'; }
    public static function titleColumn(): string { return 'Place'; }
    public static function media(): ?string { return 'hotel'; }
    public static function approval(): ?array { return Flags::approval(); }
    public static function searchColumns(): array { return [...HotelsModel::SEARCH_COLUMNS, 'EmailAddress', 'ContactNo']; }

    public static function filters(): array
    {
        return [
            ...Flags::approvalFilter(),
            'PlaceType' => ['Type', fn () => array_combine($t = static::distinct('PlaceType'), $t)],
            'City' => ['City', fn () => array_combine($c = static::distinct('City'), $c)],
            'Featured' => ['Featured', ['Yes' => 'Featured']],
            'Trending' => ['Trending', ['Yes' => 'Trending']],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('Place', 'Name'),
            Column::make('PlaceType', 'Type'),
            Column::make('City', 'City'),
            Column::make('Prices', 'From')->money(),
            Column::make('Rating', 'Stars'),
            Column::make('Approved', 'Approval')->badge(),
            Column::make('Featured', 'Featured')->badge(),
            Column::make('Trending', 'Trending')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('Place', 'Property name')->required()->rules(['min:2', 'max:120', 'not_regex:/[\/\\\\]|\.\./'])
                ->help('Rooms, amenities and photos are linked by this name; renaming moves them too.'),
            Field::select('PlaceType', 'Type', array_combine(RegisterProperty::TYPES, RegisterProperty::TYPES))->required()->default('Hotel')->span(3),
            Flags::rating(),
            Field::text('City', 'City')->required()->suggest(fn () => static::distinct('City'))->span(4),
            Field::text('Country', 'Country')->default('Uganda')->span(4),
            Field::text('coordinates', 'Coordinates')->help('latitude,longitude')->span(4),
            Field::text('ExactLocation', 'Address')->span(12),
            Field::text('ContactNo', 'Phone'),
            Field::email('EmailAddress', 'Bookings email'),
            Field::money('Prices', 'Lowest price per night')->required(),
            Field::text('PriceSpecifications', 'Price details')->rules(['max:500']),
            Field::textarea('Comment', 'Short description')->rules(['max:300']),
            Field::textarea('Description', 'Full description'),
            Field::textarea('SearchTerms', 'Search keywords')->help('Words visitors may search for: name, city, area, type…'),
            Field::yesno('TaxesInclusive', 'Taxes included')->default('Yes'),
            Field::yesno('Prepayment', 'Prepayment needed')->default('No'),
            Field::yesno('CreditCard', 'Cards accepted')->default('Yes'),
            Field::yesno('BedBreakFast', 'Breakfast included')->default('Yes'),
            Field::text('Parking', 'Parking')->suggest(['Free', 'Paid', 'No'])->default('Free')->span(3),
            Flags::approved(),
            Field::yesno('Featured', 'Featured')->default('No'),
            Field::number('FaturedRank', 'Featured order')->rules(['integer', 'min:0'])->span(3),
            Field::yesno('Trending', 'Trending')->default('No'),
            Field::number('TrendRank', 'Trending order')->rules(['integer', 'min:0'])->span(3),
        ];
    }

    /** Unique name, ignoring the record being edited. */
    public static function uniqueRules(?Model $record): array
    {
        return ['Place' => [Rule::unique('pickuplocations', 'Place')->ignore($record?->getKey(), 'IDs')]];
    }

    public static function links(Model $record): array
    {
        return [
            ['Rooms', route('admin.resources.index', ['resource' => 'rooms', 'filter' => ['HotelName' => $record->Place]]), 'bi-door-open'],
            ['Amenities & surroundings', route('admin.property-details', ['type' => 'hotel', 'id' => $record->IDs]), 'bi-list-check'],
            ['View on site', route('HotelSingle', ['hotelslug' => $record->IDs]), 'bi-box-arrow-up-right'],
        ];
    }

    public static function afterSave(Model $record, array $before): void
    {
        $from = (string) ($before['Place'] ?? '');
        $to = (string) $record->Place;
        if ($from === '' || $from === $to) {
            return;
        }

        PropertyDetailSections::rename($from, $to);

        // Rooms are linked by HotelName, and their photos are named {HotelName}{RoomName}.
        foreach (RoomsModel::where('HotelName', $from)->get() as $room) {
            $count = (int) $room->PhotoUrl;
            $oldBase = Media::baseName('room', $room);
            $room->HotelName = $to;
            $room->save();
            MediaFiles::rename('room', $oldBase, Media::baseName('room', $room), $count);
        }
    }

    public static function afterDelete(Model $record): void
    {
        PropertyDetailSections::deleteFor((string) $record->Place);
        $roomIds = RoomsModel::where('HotelName', $record->Place)->pluck('IDs');
        RoomDetailsModel::whereIn('roomid', $roomIds)->delete();
        RoomsModel::whereIn('IDs', $roomIds)->delete();
    }
}
