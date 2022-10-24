<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbySupermarkets extends Model
{
    use HasFactory;
    protected $table="nearbysupermarkets";
    public function nearbysupermarketsdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
