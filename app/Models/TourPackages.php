<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPackages extends Model
{
    use HasFactory, HasListingScopes;

    protected $table = 'tourpackage';
    protected $primaryKey = 'IDs';

    public const SEARCH_COLUMNS = ['SearchTerms', 'PackageName', 'City'];

    /** Packages whose travel date is still ahead. */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('TravelDate', '>', now()->toDateString());
    }
}
