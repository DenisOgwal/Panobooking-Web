<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbyRestaurants extends Model
{
    use HasFactory;
    protected $table="nearbyrestaurants";
    public function nearbyrestaurantsdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
