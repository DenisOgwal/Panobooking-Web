<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularAmenitiesModel extends Model
{
    use HasFactory;
    protected $table="popularamenities";
    public function hotel()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
