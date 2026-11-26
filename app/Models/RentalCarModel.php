<?php

namespace App\Models;

use App\Models\Concerns\HasListingScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalCarModel extends Model
{
    use HasFactory, HasListingScopes;

    protected $table = 'carrentals';
    protected $primaryKey = 'IDs';

    /** Mass-assignable except the key and admin-controlled flags (forms must not self-approve or promote). */
    protected $guarded = ['IDs', 'Approved', 'Trending', 'Featured', 'TrendRank', 'FaturedRank', 'Offered'];

    public const SEARCH_COLUMNS = ['SearchTerms', 'CurrentLocation', 'City', 'DriverName', 'TaxiNo', 'Company', 'CarCategory', 'CarSpec'];
}
