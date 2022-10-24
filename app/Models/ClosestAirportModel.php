<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosestAirportModel extends Model
{
    use HasFactory;
    protected $table="closestairport";
    public function closestairportdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
