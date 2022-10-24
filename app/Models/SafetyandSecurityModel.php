<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyandSecurityModel extends Model
{
    use HasFactory;
    protected $table="security";
    public function securitydetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
