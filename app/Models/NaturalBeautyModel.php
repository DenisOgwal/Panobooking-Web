<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaturalBeautyModel extends Model
{
    use HasFactory;
    protected $table="naturalbeauty";
    public function naturalbeautydetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
