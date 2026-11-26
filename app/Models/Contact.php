<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** Messages sent through the contact form. */
class Contact extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = ['Names', 'Email', 'Messages'];
}
