<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** Property submissions from /RegisterProperty (same table as HotelsModel). */
class RegisterPropertyModel extends Model
{
    use HasFactory;

    protected $table = 'pickuplocations';
    protected $primaryKey = 'IDs';

    /** Mass-assignable except the key and admin-controlled flags (forms must not self-approve or promote). */
    protected $guarded = ['IDs', 'Approved', 'Trending', 'Featured', 'TrendRank', 'FaturedRank', 'Offered'];
}
