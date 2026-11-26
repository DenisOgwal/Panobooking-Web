<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** A booking request (stay, car, taxi, ambulance, home stay or tour). */
class CartModel extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'IDs';

    protected $fillable = [
        'Facility', 'ProductName', 'User', 'UserNames', 'Quantity', 'Cost', 'OrderCode',
        'DatesFrom', 'DatesTo', 'Descriptions', 'BookingType', 'Currency', 'CurrencyAmount',
    ];

    public function scopeForCustomer(Builder $query, ?string $email): Builder
    {
        return $query->where('User', $email);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('Confirmed', 'Pending');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('Confirmed', 'Confirmed');
    }
}
