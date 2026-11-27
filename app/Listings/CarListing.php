<?php

namespace App\Listings;

use App\Models\RentalCarModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/** A rental car listed by its owner (`carrentals`, pending approval). */
class CarListing extends ListingForm
{
    public const CATEGORIES = ['Saloon', 'SUV', 'Van', 'Pickup', 'Minibus', 'Bus', 'Luxury'];

    /** Panobooking's commission added to the owner's daily rates. */
    public const COMMISSION = 0.05;

    public static function key(): string { return 'car'; }

    public function rules(): array
    {
        return [
            'plate' => [...self::SAFE_NAME, 'unique:carrentals,TaxiNo'],
            'category' => 'required|in:' . implode(',', self::CATEGORIES),
            'model' => 'required|string|max:120',
            'company' => self::SAFE_NAME,
            'location' => 'required|string|max:120',
            'kampala_rate' => 'required|numeric|min:1000',
            'upcountry_rate' => 'required|numeric|min:1000',
            'passengers' => 'required|integer|min:1|max:60',
            'doors' => 'required|integer|min:2|max:6',
            'automatic' => 'required|in:Yes,No',
            'luggage' => 'required|integer|min:0|max:20',
            'min_driver_age' => 'required|integer|min:18|max:80',
            'rating' => 'required|numeric|min:1|max:5',
            'description' => 'required|string|max:1000',
            'payment_method' => 'required|string|max:60',
            'phone' => self::PHONE,
            'email' => 'required|email|max:190',
            'driver_password' => 'required|string|min:8|max:100',
            'photo' => self::PHOTO,
        ];
    }

    public function attributes(): array
    {
        return ['plate' => 'number plate', 'category' => 'category', 'model' => 'make and model', 'company' => 'company name',
            'location' => 'location', 'kampala_rate' => 'Kampala daily rate', 'upcountry_rate' => 'upcountry daily rate',
            'passengers' => 'passengers', 'doors' => 'doors', 'luggage' => 'luggage', 'min_driver_age' => 'minimum driver age',
            'rating' => 'rating', 'payment_method' => 'payment method', 'phone' => 'phone number', 'driver_password' => 'password', 'photo' => 'photo'];
    }

    public function store(array $data, UploadedFile $photo): Model
    {
        $withCommission = fn ($v) => round((float) $v * (1 + self::COMMISSION), 2);

        $car = RentalCarModel::create([
            'DriverName' => $data['company'], 'Company' => $data['company'], 'TaxiNo' => $data['plate'],
            'Ratings' => $data['rating'], 'DriverAge' => $data['min_driver_age'], 'CarSpec' => $data['model'],
            'Repayments' => $data['payment_method'],
            'Price' => $withCommission($data['kampala_rate']), 'PricePerDay' => $withCommission($data['upcountry_rate']),
            'CurrentLocation' => $data['location'], 'City' => $data['location'],
            'EmailAddress' => $data['email'], 'TelephoneNumber' => $data['phone'],
            // Driver passwords are checked by the separate driver app, which uses MD5.
            'Password' => md5($data['driver_password']),
            'Passengers' => $data['passengers'], 'Laggages' => $data['luggage'], 'Automatic' => $data['automatic'],
            'Doors' => $data['doors'], 'Description' => $data['description'], 'CarCategory' => $data['category'],
            'SearchTerms' => trim("{$data['model']} {$data['category']} {$data['location']} {$data['company']}"),
        ]);
        $this->storePhoto($photo, 'AndroidDriver/FileUpload', $data['company'] . $data['plate']);

        return $car;
    }
}
