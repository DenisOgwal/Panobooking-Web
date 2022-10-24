<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomDetailsModel extends Model
{
    use HasFactory;
    protected $table="roomfeatures";
    public function room()
    {
        return $this->belongsTo(RoomsModel::class);
    }
    public function rooms()
    {
        return $this->belongsTo(HomeGuestModel::class);
    }
}
