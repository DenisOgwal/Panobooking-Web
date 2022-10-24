<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingModel extends Model
{
    use HasFactory;
    protected $table="parking";
    public function parkingdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
