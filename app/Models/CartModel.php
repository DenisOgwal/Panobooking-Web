<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    use HasFactory;
    protected $table="cart";
    protected $fillable = [
        'Facility', 
        'ProductName',
        'User',
        'UserNames',
        'Quantity',
        'Cost',
        'OrderCode',
        'DatesFrom',
        'DatesTo',
        'Descriptions',
    ];
}
