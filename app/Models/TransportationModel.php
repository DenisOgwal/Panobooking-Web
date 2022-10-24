<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationModel extends Model
{
    use HasFactory;
    protected $table="transportation";
    public function transportationdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
