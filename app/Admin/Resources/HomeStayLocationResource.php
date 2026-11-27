<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\HomeGuestLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/** Cities offered for home stays (`homeguestlocations`). */
class HomeStayLocationResource extends Resource
{
    public static function model(): string { return HomeGuestLocation::class; }
    public static function label(): string { return 'Home-stay cities'; }
    public static function singular(): string { return 'home-stay city'; }
    public static function icon(): string { return 'bi-pin-map'; }
    public static function group(): string { return 'Stays'; }
    public static function titleColumn(): string { return 'City'; }
    public static function defaultSort(): array { return ['City', 'asc']; }

    public static function columns(): array
    {
        return [Column::make('City', 'City')];
    }

    public static function fields(): array
    {
        return [Field::text('City', 'City')->required()];
    }

    public static function uniqueRules(?Model $record): array
    {
        return ['City' => [Rule::unique('homeguestlocations', 'City')->ignore($record?->getKey(), 'IDs')]];
    }
}
