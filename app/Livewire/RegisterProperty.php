<?php

namespace App\Livewire;

use App\Livewire\Concerns\RegistersListing;
use App\Models\RegisterPropertyModel;
use Livewire\Component;

/** /RegisterProperty: list a hotel, lodge, apartment… (pending approval). */
class RegisterProperty extends Component
{
    use RegistersListing;

    public const TYPES = ['Hotel', 'Apartment', 'Lodge', 'Resort', 'Cottage', 'Guest House', 'Villa', 'Country Home'];

    public string $place = '';
    public string $placetype = 'Hotel';
    public string $country = 'Uganda';
    public string $region = '';
    public string $city = '';
    public string $exactlocation = '';
    public $leastprice = '';
    public string $pricedescription = '';
    public string $taxesinclusive = 'Yes';
    public string $paymentoption = 'No';
    public string $creditcard = 'Yes';
    public string $bedandbreakfast = 'Yes';
    public string $slogan = '';
    public $ratings = '';
    public string $telephonenumber = '';
    public string $email = '';

    protected function rules(): array
    {
        return [
            'place' => [...self::SAFE_NAME, 'unique:pickuplocations,Place'],
            'placetype' => 'required|in:' . implode(',', self::TYPES),
            'country' => 'required|string|max:80',
            'region' => 'required|string|max:80',
            'city' => 'required|string|max:80',
            'exactlocation' => 'required|string|max:200',
            'leastprice' => 'required|numeric|min:1000',
            'pricedescription' => 'required|string|max:500',
            'taxesinclusive' => 'required|in:Yes,No',
            'paymentoption' => 'required|in:Yes,No',
            'creditcard' => 'required|in:Yes,No',
            'bedandbreakfast' => 'required|in:Yes,No',
            'slogan' => 'required|string|max:300',
            'ratings' => 'required|numeric|min:1|max:5',
            'telephonenumber' => ['required', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'email' => 'required|email|max:190',
            'formFile' => self::PHOTO,
        ];
    }

    protected function validationAttributes(): array
    {
        return ['place' => 'property name', 'placetype' => 'property type', 'exactlocation' => 'address', 'leastprice' => 'lowest price',
            'pricedescription' => 'price details', 'slogan' => 'short description', 'ratings' => 'star rating',
            'telephonenumber' => 'phone number', 'formFile' => 'photo'];
    }

    public function submit(): void
    {
        $this->validate();

        RegisterPropertyModel::create([
            'Place' => $this->place, 'PlaceType' => $this->placetype, 'City' => $this->city, 'Country' => $this->country,
            'Rating' => $this->ratings, 'Comment' => $this->slogan, 'Prices' => $this->leastprice,
            'PriceSpecifications' => $this->pricedescription, 'TaxesInclusive' => $this->taxesinclusive,
            'Prepayment' => $this->paymentoption, 'BedBreakFast' => $this->bedandbreakfast, 'CreditCard' => $this->creditcard,
            'ExactLocation' => $this->exactlocation, 'EmailAddress' => $this->email, 'ContactNo' => $this->telephonenumber,
            'SearchTerms' => trim("{$this->place} {$this->city} {$this->region} {$this->placetype}"),
        ]);
        $this->storePhoto('AndroidFiles/DestinationImages', $this->place);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.register-property')->layout('components.layouts.app', ['title' => 'List your property']);
    }
}
