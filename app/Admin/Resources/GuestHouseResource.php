<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\PropertyDetailSections;
use App\Admin\Resource;
use App\Models\HomeGuestLocation;
use App\Models\HomeGuestModel;
use App\Models\RoomDetailsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/** Home stays (`homeguesthouses`, named by `GuestHouseName`). */
class GuestHouseResource extends Resource
{
    public static function model(): string { return HomeGuestModel::class; }
    public static function slug(): string { return 'home-stays'; }
    public static function label(): string { return 'Home stays'; }
    public static function singular(): string { return 'home stay'; }
    public static function icon(): string { return 'bi-house-heart'; }
    public static function group(): string { return 'Stays'; }
    public static function titleColumn(): string { return 'GuestHouseName'; }
    public static function media(): ?string { return 'guesthouse'; }
    public static function searchColumns(): array { return ['GuestHouseName', 'City', 'Place', 'ExactLocation', 'EmailAddress']; }

    public static function filters(): array
    {
        return [
            'City' => ['City', fn () => array_combine($c = static::distinct('City'), $c)],
            'Availability' => ['Availability', ['Available' => 'Available', 'Unavailable' => 'Unavailable']],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('GuestHouseName', 'Name'),
            Column::make('City', 'City'),
            Column::make('Prices', 'From')->money(),
            Column::make('NoOfRooms', 'Rooms'),
            Column::make('Rating', 'Stars'),
            Column::make('Availability', 'Availability')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('GuestHouseName', 'Name')->required()->rules(['min:2', 'max:120', 'not_regex:/[\/\\\\]|\.\./'])
                ->help('Amenities and photos are linked by this name; renaming moves them too.'),
            Flags::rating(),
            Field::number('NoOfRooms', 'Rooms')->rules(['integer', 'min:0'])->span(3),
            Field::text('City', 'City')->required()->suggest(fn () => HomeGuestLocation::orderBy('City')->pluck('City')->all())->span(4),
            Field::text('Place', 'Area / neighbourhood')->span(4),
            Field::text('Coordinates', 'Coordinates')->help('latitude,longitude')->span(4),
            Field::text('ExactLocation', 'Address')->span(12),
            Field::email('EmailAddress', 'Bookings email'),
            Field::money('Prices', 'Price per night')->required(),
            Field::textarea('Description', 'Description'),
            Field::yesno('BedBreakFast', 'Breakfast included')->default('Yes'),
            Field::yesno('CreditCard', 'Cards accepted')->default('No'),
            Field::text('Parking', 'Parking')->suggest(['Free', 'Paid', 'No'])->default('Free')->span(3),
            Flags::availability(),
            Flags::offered(),
        ];
    }

    public static function uniqueRules(?Model $record): array
    {
        return ['GuestHouseName' => [Rule::unique('homeguesthouses', 'GuestHouseName')->ignore($record?->getKey(), 'IDs')]];
    }

    /** The site reads a home stay's room features from `roomfeatures` by RoomName = GuestHouseName. */
    public static function checklist(): ?array
    {
        return [
            'label' => 'Room features',
            'model' => RoomDetailsModel::class,
            'match' => fn (array $a) => ['RoomName' => $a['GuestHouseName'] ?? null],
            'text' => ['Feet' => 'Room size'],
        ];
    }

    public static function links(Model $record): array
    {
        return [
            ['Amenities & surroundings', route('admin.property-details', ['type' => 'guesthouse', 'id' => $record->IDs]), 'bi-list-check'],
            ['View on site', route('HomeGuestSingle', ['homeguestslug' => $record->IDs]), 'bi-box-arrow-up-right'],
        ];
    }

    public static function afterSave(Model $record, array $before): void
    {
        PropertyDetailSections::rename((string) ($before['GuestHouseName'] ?? ''), (string) $record->GuestHouseName);
    }

    public static function afterDelete(Model $record): void
    {
        PropertyDetailSections::deleteFor((string) $record->GuestHouseName);
    }
}
