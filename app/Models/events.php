<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class events extends Model
{
    use HasFactory, HasListingScopes;

    protected $table = 'events';

    public const SEARCH_COLUMNS = ['SearchKeyWords', 'EventName', 'City'];

    /** Events that have not finished yet. */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('EndDate', '>=', now()->toDateString());
    }
}
