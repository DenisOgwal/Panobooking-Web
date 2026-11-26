<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** Airport taxis. Unlike cars and ambulances, this table has no approval column. */
class TaxiModel extends Model
{
    use HasFactory, HasListingScopes;

    protected $table = 'airporttaxi';
    protected $primaryKey = 'IDs';

    public const SEARCH_COLUMNS = ['SearchTerms', 'CurrentLocation', 'DriverName', 'TaxiNo', 'CarCategory', 'CarSpec'];

    /**
     * Taxis relevant to a place.
     *
     * Airport taxis drive from the airport to the destinations listed in `taxitrip`, so when the
     * place is one of those destinations every taxi is relevant. Otherwise match the place (and any
     * extra terms) against the taxi's location and search terms.
     */
    public function scopeForDestination(Builder $query, ?string $place, array $extraTerms = []): Builder
    {
        if (self::servesDestination($place)) {
            return $query;
        }

        return $query->searchAny([$place, ...$extraTerms], ['CurrentLocation', 'SearchTerms']);
    }

    /** Whether any airport-taxi trip goes to (a place matching) $place. */
    public static function servesDestination(?string $place): bool
    {
        $place = trim((string) $place);

        return $place !== ''
            && TaxiTripModel::where('Tos', 'like', '%' . addcslashes($place, '%_\\') . '%')->exists();
    }
}
