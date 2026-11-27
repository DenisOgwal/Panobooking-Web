<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Cities offered for home stays (`homeguestlocations`). */
class HomeGuestLocation extends Model
{
    protected $table = 'homeguestlocations';
    protected $primaryKey = 'IDs';
    protected $guarded = ['IDs'];
}
