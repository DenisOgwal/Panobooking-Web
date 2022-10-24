<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseRulesModel extends Model
{
    use HasFactory;
    protected $table="houserules";
    public function houserulesdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
