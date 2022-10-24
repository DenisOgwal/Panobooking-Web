<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTransportModel extends Model
{
    use HasFactory;
    protected $table="publictransport";
    public function publictransportdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
