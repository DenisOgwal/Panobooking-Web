<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaModel extends Model
{
    use HasFactory;
    protected $table="spa";
    public function spadetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
