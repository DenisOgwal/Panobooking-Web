<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use App\Models\Concerns\HasPropertyDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Hotels and other stays (table `pickuplocations`, keyed by property name in `Place`). */
class HotelsModel extends Model
{
    use HasFactory, HasListingScopes, HasPropertyDetails;

    protected $table = 'pickuplocations';
    protected $primaryKey = 'IDs';

    /** Columns matched by the free-text search. */
    public const SEARCH_COLUMNS = ['SearchTerms', 'City', 'PlaceType', 'Place'];

    protected function propertyNameColumn(): string
    {
        return 'Place';
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(RoomsModel::class, 'HotelName', 'Place');
    }
}
