<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenModel extends Model
{
    use HasFactory;
    protected $table="kitchen";
    public function kitchendetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
