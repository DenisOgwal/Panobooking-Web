<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodandDrinkModel extends Model
{
    use HasFactory;
    protected $table="foodanddrink";
    public function fooddetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
