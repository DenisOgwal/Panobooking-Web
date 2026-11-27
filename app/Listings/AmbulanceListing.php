<?php

namespace App\Listings;

use App\Models\AmbulanceModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/** An ambulance listed by its operator (`ambulances`, pending approval). */
class AmbulanceListing extends ListingForm
{
    public const CATEGORIES = ['Advanced Life Support', 'Basic Life Support', 'Patient Transport', 'Neonatal', 'Air Ambulance'];

    public static function key(): string { return 'ambulance'; }

    public function rules(): array
    {
        return [
            'plate' => [...self::SAFE_NAME, 'unique:ambulances,TaxiNo'],
            'category' => 'required|in:' . implode(',', self::CATEGORIES),
            'operator' => 'required|string|max:120',
            'location' => 'required|string|max:120',
            'vehicle' => 'required|string|max:200',
            'payment_method' => 'required|string|max:60',
            'phone' => self::PHONE,
            'email' => 'required|email|max:190',
            'driver_password' => 'required|string|min:8|max:100',
            'photo' => self::PHOTO,
        ];
    }

    public function attributes(): array
    {
        return ['plate' => 'number plate', 'category' => 'category', 'operator' => 'operator name', 'location' => 'base location',
            'vehicle' => 'vehicle', 'payment_method' => 'payment method', 'phone' => 'phone number', 'driver_password' => 'password', 'photo' => 'photo'];
    }

    public function store(array $data, UploadedFile $photo): Model
    {
        $ambulance = AmbulanceModel::create([
            'DriverName' => $data['operator'], 'TaxiNo' => $data['plate'], 'AmbulanceCategory' => $data['category'],
            'Repayments' => $data['payment_method'], 'CurrentLocation' => $data['location'], 'City' => $data['location'],
            'CarSpec' => $data['vehicle'],
            // Driver passwords are checked by the separate driver app, which uses MD5.
            'Password' => md5($data['driver_password']),
            'EmailAddress' => $data['email'], 'TelephoneNumber' => $data['phone'],
            'SearchTerms' => trim("{$data['category']} ambulance {$data['location']}"),
        ]);
        $this->storePhoto($photo, 'AndroidDriver/FileUpload', $data['plate']);

        return $ambulance;
    }
}
