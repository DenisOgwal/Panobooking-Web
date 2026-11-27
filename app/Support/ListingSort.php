<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

/** Sort orders shared by the website lists (SortsListings) and the API (`?sort=`). */
final class ListingSort
{
    public const OPTIONS = [
        'recommended' => 'Recommended',
        'price_asc' => 'Price: low to high',
        'price_desc' => 'Price: high to low',
        'rating' => 'Top rated',
        'name' => 'Name A–Z',
    ];

    public static function valid(?string $sort): string
    {
        return array_key_exists((string) $sort, self::OPTIONS) ? (string) $sort : 'recommended';
    }

    /**
     * "recommended" uses the rank column when given (unranked last, then by name), otherwise newest first.
     */
    public static function apply(Builder $query, ?string $sort, string $priceColumn, string $nameColumn,
        string $ratingColumn = 'Rating', ?string $rankColumn = null): Builder
    {
        return match (self::valid($sort)) {
            'price_asc' => $query->orderByRaw("{$priceColumn} IS NULL")->orderBy($priceColumn),
            'price_desc' => $query->orderByDesc($priceColumn),
            'rating' => $query->orderByDesc($ratingColumn),
            'name' => $query->orderBy($nameColumn),
            default => $rankColumn
                ? $query->orderByRaw("{$rankColumn} IS NULL")->orderBy($rankColumn)->orderBy($nameColumn)
                : $query->orderByDesc($query->getModel()->getKeyName()),
        };
    }
}
