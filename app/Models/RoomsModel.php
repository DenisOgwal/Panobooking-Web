<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomsModel extends Model
{
    use HasFactory;
    protected $table="rooms";
    public function roomdetails()
    {
        return $this->hasOne('App\Models\RoomDetailsModel','roomid','IDs');
    }
}
