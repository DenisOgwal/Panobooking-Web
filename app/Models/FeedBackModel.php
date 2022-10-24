<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBackModel extends Model
{
    use HasFactory;
    protected $table="clientfeedback";
    protected $fillable = [
        'ClientName', 
        'ClientEmail',
        'ClientJob',
        'ClientCompany',
        'FeedBack',
    ];
}
