<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopAttractionsModel extends Model
{
    use HasFactory;
    protected $table="topattractions";
    public function topattractionsdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
