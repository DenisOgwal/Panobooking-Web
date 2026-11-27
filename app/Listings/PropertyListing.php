<?php

namespace App\Listings;

use App\Models\RegisterPropertyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/** A hotel, lodge, apartment… listed by its owner (`pickuplocations`, pending approval). */
class PropertyListing extends ListingForm
{
    public const TYPES = ['Hotel', 'Apartment', 'Lodge', 'Resort', 'Cottage', 'Guest House', 'Villa', 'Country Home'];

    public static function key(): string { return 'property'; }

    public function rules(): array
    {
        return [
            'name' => [...self::SAFE_NAME, 'unique:pickuplocations,Place'],
            'type' => 'required|in:' . implode(',', self::TYPES),
            'country' => 'required|string|max:80',
            'region' => 'required|string|max:80',
            'city' => 'required|string|max:80',
            'address' => 'required|string|max:200',
            'lowest_price' => 'required|numeric|min:1000',
            'price_details' => 'required|string|max:500',
            'taxes_included' => 'required|in:Yes,No',
            'prepayment' => 'required|in:Yes,No',
            'cards_accepted' => 'required|in:Yes,No',
            'breakfast_included' => 'required|in:Yes,No',
            'short_description' => 'required|string|max:300',
            'rating' => 'required|numeric|min:1|max:5',
            'phone' => self::PHONE,
            'email' => 'required|email|max:190',
            'photo' => self::PHOTO,
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'property name', 'type' => 'property type', 'address' => 'address', 'lowest_price' => 'lowest price',
            'price_details' => 'price details', 'short_description' => 'short description', 'rating' => 'star rating',
            'phone' => 'phone number', 'photo' => 'photo'];
    }

    public function store(array $data, UploadedFile $photo): Model
    {
        $property = RegisterPropertyModel::create([
            'Place' => $data['name'], 'PlaceType' => $data['type'], 'City' => $data['city'], 'Country' => $data['country'],
            'Rating' => $data['rating'], 'Comment' => $data['short_description'], 'Prices' => $data['lowest_price'],
            'PriceSpecifications' => $data['price_details'], 'TaxesInclusive' => $data['taxes_included'],
            'Prepayment' => $data['prepayment'], 'BedBreakFast' => $data['breakfast_included'], 'CreditCard' => $data['cards_accepted'],
            'ExactLocation' => $data['address'], 'EmailAddress' => $data['email'], 'ContactNo' => $data['phone'],
            'SearchTerms' => trim("{$data['name']} {$data['city']} {$data['region']} {$data['type']}"),
        ]);
        $this->storePhoto($photo, 'AndroidFiles/DestinationImages', $data['name']);

        return $property;
    }
}
