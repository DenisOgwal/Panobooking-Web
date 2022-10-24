<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbyPlacesModel extends Model
{
    use HasFactory;
    protected $table="nearbyplaces";
    public function nearbyplacesdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
