<?php

namespace App\Livewire;

use App\Listings\ListingForm;
use App\Listings\PropertyListing;
use App\Livewire\Concerns\RegistersListing;
use Livewire\Component;

/** /RegisterProperty: list a hotel, lodge, apartment… (pending approval; rules: App\Listings\PropertyListing). */
class RegisterProperty extends Component
{
    use RegistersListing;

    public const TYPES = PropertyListing::TYPES;

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

    protected function listingForm(): ListingForm { return app(PropertyListing::class); }

    protected function listingFields(): array
    {
        return [
            'name' => 'place', 'type' => 'placetype', 'country' => 'country', 'region' => 'region', 'city' => 'city',
            'address' => 'exactlocation', 'lowest_price' => 'leastprice', 'price_details' => 'pricedescription',
            'taxes_included' => 'taxesinclusive', 'prepayment' => 'paymentoption', 'cards_accepted' => 'creditcard',
            'breakfast_included' => 'bedandbreakfast', 'short_description' => 'slogan', 'rating' => 'ratings',
            'phone' => 'telephonenumber', 'email' => 'email', 'photo' => 'formFile',
        ];
    }

    public function submit(): void
    {
        $this->submitListing();
    }

    public function render()
    {
        return view('livewire.register-property')->layout('components.layouts.app', ['title' => 'List your property']);
    }
}
