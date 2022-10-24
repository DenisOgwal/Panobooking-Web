<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbulanceAccessoriesModel extends Model
{
    use HasFactory;
    protected $table="ambulanceaccessories";
    public function Ambulancedetails()
    {
        return $this->belongsTo(AmbulanceModel::class);
    }
}
