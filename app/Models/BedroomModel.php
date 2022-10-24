<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedroomModel extends Model
{
    use HasFactory;
    protected $table="bedroom";
    public function bedroomdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
