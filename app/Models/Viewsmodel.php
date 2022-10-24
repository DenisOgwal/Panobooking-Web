<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viewsmodel extends Model
{
    use HasFactory;
    protected $table="view";
    public function viewdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
