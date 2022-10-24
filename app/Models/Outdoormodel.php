<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outdoormodel extends Model
{
    use HasFactory;
    protected $table="outdoor";
    public function outdoordetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
