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

    public const SEARCH_COLUMNS = ['SearchTerms', 'CurrentLocation', 'City', 'DriverName', 'TaxiNo', 'Company', 'CarCategory', 'CarSpec'];
}
