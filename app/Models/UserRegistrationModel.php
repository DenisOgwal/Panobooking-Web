<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRegistrationModel extends Model
{
    use HasFactory;
    protected $table="registration";
    protected $fillable = [
        'FullNames', 
        'Email', 
        'Password',
        'Telephone',
        'ApprovalStatus',
    ];
}
