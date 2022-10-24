<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaandTechModel extends Model
{
    use HasFactory;
    protected $table="mediaandtechnology";
    public function mediaandtechnologydetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
