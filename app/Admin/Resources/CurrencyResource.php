<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\CurrencySelect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/** Display currencies (`currency`). Prices are stored in UGX; CurrencyValue = UGX per unit. */
class CurrencyResource extends Resource
{
    public static function model(): string { return CurrencySelect::class; }
    public static function label(): string { return 'Currencies'; }
    public static function singular(): string { return 'currency'; }
    public static function icon(): string { return 'bi-currency-exchange'; }
    public static function group(): string { return 'Settings'; }
    public static function titleColumn(): string { return 'CurrencyName'; }
    public static function defaultSort(): array { return ['CurrencyName', 'asc']; }
    public static function adminOnly(): bool { return true; }
    public static function cacheKeys(): array { return ['currency.rates']; }

    public static function columns(): array
    {
        return [
            Column::make('CurrencyName', 'Code'),
            Column::make('CurrencyValue', 'UGX per unit'),
            Column::make('updated_at', 'Updated')->date(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('CurrencyName', 'Code')->required()->rules(['regex:/^[A-Z]{3}$/'])->help('ISO code, e.g. USD')->span(4),
            Field::number('CurrencyValue', 'UGX per unit')->required()->rules(['gt:0'])->help('e.g. 3700 for USD; UGX itself is 1')->span(4),
        ];
    }

    public static function uniqueRules(?Model $record): array
    {
        return ['CurrencyName' => [Rule::unique('currency', 'CurrencyName')->ignore($record?->getKey())]];
    }

    public static function preventDelete(Model $record): ?string
    {
        return $record->CurrencyName === 'UGX' ? 'UGX is the base currency and cannot be deleted.' : null;
    }
}
