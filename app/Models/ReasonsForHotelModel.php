<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonsForHotelModel extends Model
{
    use HasFactory;
    protected $table="reasonstostayatproperty";
    public function reasonstostayatpropertydetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
