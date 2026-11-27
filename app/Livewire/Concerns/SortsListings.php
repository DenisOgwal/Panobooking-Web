<?php

namespace App\Livewire\Concerns;

use App\Support\ListingSort;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/**
 * Sorting + pagination for listing grids. The sort is kept in the URL (?sort=price_asc)
 * so results can be shared and survive reloads. Orders come from App\Support\ListingSort,
 * which the mobile API uses too.
 */
trait SortsListings
{
    use WithPagination;

    #[Url(as: 'sort', except: 'recommended')]
    public string $sort = 'recommended';

    public int $perPage = 12;

    public function sortOptions(): array
    {
        return ListingSort::OPTIONS;
    }

    public function updatedSort(): void
    {
        if (! array_key_exists($this->sort, $this->sortOptions())) {
            $this->sort = 'recommended';
        }
        $this->resetPage();
    }

    protected function applySort(Builder $query, string $priceColumn, string $nameColumn, string $ratingColumn = 'Rating', ?string $rankColumn = null): Builder
    {
        return ListingSort::apply($query, $this->sort, $priceColumn, $nameColumn, $ratingColumn, $rankColumn);
    }
}
