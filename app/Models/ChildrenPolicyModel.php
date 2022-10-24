<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenPolicyModel extends Model
{
    use HasFactory;
    protected $table="childrenpolicies";
    public function childrenpoliciesdetails()
    {
        return $this->belongsTo(HotelsModel::class);
    }
}
