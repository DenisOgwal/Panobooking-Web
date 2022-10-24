<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BathroomModel extends Model
{
    use HasFactory;
    protected $table="bathroom";
    public function bathroomdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
