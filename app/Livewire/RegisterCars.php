<?php

namespace App\Livewire;

use App\Listings\CarListing;
use App\Listings\ListingForm;
use App\Livewire\Concerns\RegistersListing;
use Livewire\Component;

/** /RegisterCars: list a rental car (pending approval; rules: App\Listings\CarListing). */
class RegisterCars extends Component
{
    use RegistersListing;

    public const CATEGORIES = CarListing::CATEGORIES;

    /** Panobooking's commission added to the owner's daily rates. */
    public const COMMISSION = CarListing::COMMISSION;

    public string $carname = '';
    public string $carcategory = 'Saloon';
    public string $CarSpecs = '';
    public string $Carcompany = '';
    public string $Carlocation = '';
    public $kampaladrive = '';
    public $upcountrydrive = '';
    public $noofpassengers = 4;
    public $noofdoors = 4;
    public string $automaticoption = 'Yes';
    public $laggagesoption = 2;
    public $driversage = 23;
    public $carratings = '';
    public string $description = '';
    public string $paymentoption = 'Cash';
    public string $telphonenumber = '';
    public string $email = '';
    public string $pswd = '';

    protected function listingForm(): ListingForm { return app(CarListing::class); }

    protected function listingFields(): array
    {
        return [
            'plate' => 'carname', 'category' => 'carcategory', 'model' => 'CarSpecs', 'company' => 'Carcompany',
            'location' => 'Carlocation', 'kampala_rate' => 'kampaladrive', 'upcountry_rate' => 'upcountrydrive',
            'passengers' => 'noofpassengers', 'doors' => 'noofdoors', 'automatic' => 'automaticoption', 'luggage' => 'laggagesoption',
            'min_driver_age' => 'driversage', 'rating' => 'carratings', 'description' => 'description',
            'payment_method' => 'paymentoption', 'phone' => 'telphonenumber', 'email' => 'email', 'driver_password' => 'pswd',
            'photo' => 'formFile',
        ];
    }

    public function submit(): void
    {
        $this->submitListing();
        $this->reset('pswd');
    }

    public function render()
    {
        return view('livewire.register-cars')->layout('components.layouts.app', ['title' => 'Register a car']);
    }
}
