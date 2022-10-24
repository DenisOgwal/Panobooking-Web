<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CribandExtrabedModel extends Model
{
    use HasFactory;
    protected $table="childandextrabed";
    public function childrenextrabeddetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
