<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomsWithModel extends Model
{
    use HasFactory;
    protected $table="roomswith";
    public function room()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
