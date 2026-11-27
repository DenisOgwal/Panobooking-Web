<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** A booking request (stay, car, taxi, ambulance, home stay or tour). */
class CartModel extends Model
{
    use HasFactory;

    /** Values of the `Confirmed` status column. */
    public const PENDING = 'Pending';
    public const CONFIRMED = 'Confirmed';
    public const DECLINED = 'Declined';
    public const STATUSES = [self::PENDING, self::CONFIRMED, self::DECLINED];

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

    /** Bookings the provider has answered (confirmed or declined). */
    public function scopeDecided(Builder $query): Builder
    {
        return $query->whereIn('Confirmed', [self::CONFIRMED, self::DECLINED]);
    }

    /** Free-text filter over what the customer sees in their bookings list. */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }
        $like = '%' . addcslashes($term, '%_\\') . '%';

        return $query->where(fn ($q) => $q->where('Facility', 'like', $like)
            ->orWhere('ProductName', 'like', $like)
            ->orWhere('BookingType', 'like', $like)
            ->orWhere('Descriptions', 'like', $like));
    }

    /** Icon for the booking type. */
    public function getTypeIconAttribute(): string
    {
        return match ($this->BookingType) {
            'Car Rental' => 'bi-car-front',
            'Airport Taxi' => 'bi-taxi-front',
            'Ambulance' => 'bi-heart-pulse',
            'Tour Package' => 'bi-compass',
            'Home Guest House' => 'bi-house-heart',
            default => 'bi-building',
        };
    }
}
