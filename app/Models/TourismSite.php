<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismSite extends Model
{
    use HasFactory, HasListingScopes;

    protected $table = 'tourismsites';

    public const SEARCH_COLUMNS = ['SearchKeyWords', 'SiteName', 'City'];
}
