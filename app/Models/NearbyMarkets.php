<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbyMarkets extends Model
{
    use HasFactory;
    protected $table="nearbymarkets";
    public function nearbymarketsdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
