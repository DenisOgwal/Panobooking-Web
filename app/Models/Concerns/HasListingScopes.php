<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Query scopes shared by listings (hotels, guest houses, cars, taxis, ambulances, rooms).
 *
 * The flag columns store words rather than booleans ('Approved', 'Yes', 'Available', 'Offered'),
 * so the scopes keep those exact values in one place.
 */
trait HasListingScopes
{
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where($query->qualifyColumn('Approved'), 'Approved');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where($query->qualifyColumn('Availability'), 'Available');
    }

    public function scopeOffered(Builder $query): Builder
    {
        return $query->where($query->qualifyColumn('Offered'), 'Offered');
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where($query->qualifyColumn('Trending'), 'Yes');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where($query->qualifyColumn('Featured'), 'Yes');
    }

    /**
     * Case-insensitive "contains" search across several columns.
     *
     * The OR conditions are grouped in their own parentheses, so they can't escape
     * other constraints on the query (e.g. ->approved()->search(...) stays approved-only).
     * An empty term leaves the query unfiltered.
     */
    public function scopeSearch(Builder $query, ?string $term, array $columns): Builder
    {
        $term = trim((string) $term);
        if ($term === '' || $columns === []) {
            return $query;
        }

        return $this->scopeSearchAny($query, [$term], $columns);
    }

    /**
     * Like search(), but matches if ANY of several terms appears in ANY of the columns.
     * Empty terms are ignored; if none remain the query is left unfiltered.
     */
    public function scopeSearchAny(Builder $query, array $terms, array $columns): Builder
    {
        $likes = collect($terms)
            ->map(fn ($t) => trim((string) $t))
            ->filter(fn ($t) => $t !== '')
            ->unique()
            ->map(fn ($t) => '%' . addcslashes($t, '%_\\') . '%')
            ->values();

        if ($likes->isEmpty() || $columns === []) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($columns, $likes) {
            foreach ($likes as $like) {
                foreach ($columns as $column) {
                    $q->orWhere($q->qualifyColumn($column), 'like', $like);
                }
            }
        });
    }
}
