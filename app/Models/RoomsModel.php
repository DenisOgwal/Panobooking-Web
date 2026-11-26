<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RoomsModel extends Model
{
    use HasFactory, HasListingScopes;

    protected $table = 'rooms';
    protected $primaryKey = 'IDs';

    public function roomdetails(): HasOne
    {
        return $this->hasOne(RoomDetailsModel::class, 'roomid', 'IDs');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(HotelsModel::class, 'HotelName', 'Place');
    }
}
