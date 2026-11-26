<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use App\Models\Concerns\HasPropertyDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/** Home guest houses (keyed by property name in `GuestHouseName`). */
class HomeGuestModel extends Model
{
    use HasFactory, HasListingScopes, HasPropertyDetails;

    protected $table = 'homeguesthouses';
    protected $primaryKey = 'IDs';

    public const SEARCH_COLUMNS = ['GuestHouseName', 'City', 'Place', 'ExactLocation'];

    protected function propertyNameColumn(): string
    {
        return 'GuestHouseName';
    }

    public function roomdetails(): HasOne
    {
        return $this->hasOne(RoomDetailsModel::class, 'RoomName', 'GuestHouseName');
    }
}
