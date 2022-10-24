<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnsiteRestaurantModel extends Model
{
    use HasFactory;
    protected $table="restaurantsonsite";
    public function restaurantsonsitedetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
