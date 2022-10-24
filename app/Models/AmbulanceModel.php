<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbulanceModel extends Model
{
    use HasFactory;
    protected $table="ambulances";
    public function Ambulancedetails()
    {
        return $this->hasOne('App\Models\AmbulanceAccessoriesModel','Ambulance','TaxiNo');
    }
}
