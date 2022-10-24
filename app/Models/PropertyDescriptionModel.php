<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDescriptionModel extends Model
{
    use HasFactory;
    protected $table="propertydescription";
    public function propertydescriptions()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
