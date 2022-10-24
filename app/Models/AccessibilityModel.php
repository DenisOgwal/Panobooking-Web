<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessibilityModel extends Model
{
    use HasFactory;
    protected $table="accessibility";
    public function accessibilitydetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
