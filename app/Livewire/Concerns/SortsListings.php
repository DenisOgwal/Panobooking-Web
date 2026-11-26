<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/**
 * Sorting + pagination for listing grids. The sort is kept in the URL (?sort=price_asc)
 * so results can be shared and survive reloads.
 */
trait SortsListings
{
    use WithPagination;

    #[Url(as: 'sort', except: 'recommended')]
    public string $sort = 'recommended';

    public int $perPage = 12;

    public function sortOptions(): array
    {
        return [
            'recommended' => 'Recommended',
            'price_asc' => 'Price: low to high',
            'price_desc' => 'Price: high to low',
            'rating' => 'Top rated',
            'name' => 'Name A–Z',
        ];
    }

    public function updatedSort(): void
    {
        if (! array_key_exists($this->sort, $this->sortOptions())) {
            $this->sort = 'recommended';
        }
        $this->resetPage();
    }

    /**
     * @param  string|null  $rankColumn  used for "recommended" (e.g. TrendRank); falls back to newest first
     */
    protected function applySort(Builder $query, string $priceColumn, string $nameColumn, string $ratingColumn = 'Rating', ?string $rankColumn = null): Builder
    {
        return match ($this->sort) {
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
