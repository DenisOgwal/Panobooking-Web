<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleaningServicesModel extends Model
{
    use HasFactory;
    protected $table="cleaning";
    public function cleaningdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
