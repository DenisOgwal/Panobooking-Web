<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessFacilityModel extends Model
{
    use HasFactory;
    protected $table="businessfacilities";
    public function businessfacilitiesdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
