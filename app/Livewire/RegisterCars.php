<?php

namespace App\Livewire;

use App\Livewire\Concerns\RegistersListing;
use App\Models\RentalCarModel;
use Livewire\Component;

/** /RegisterCars: list a rental car (pending approval). */
class RegisterCars extends Component
{
    use RegistersListing;

    public const CATEGORIES = ['Saloon', 'SUV', 'Van', 'Pickup', 'Minibus', 'Bus', 'Luxury'];

    /** Panobooking's commission added to the owner's daily rates. */
    public const COMMISSION = 0.05;

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

    protected function rules(): array
    {
        return [
            'carname' => [...self::SAFE_NAME, 'unique:carrentals,TaxiNo'],
            'carcategory' => 'required|in:' . implode(',', self::CATEGORIES),
            'CarSpecs' => 'required|string|max:120',
            'Carcompany' => self::SAFE_NAME,
            'Carlocation' => 'required|string|max:120',
            'kampaladrive' => 'required|numeric|min:1000',
            'upcountrydrive' => 'required|numeric|min:1000',
            'noofpassengers' => 'required|integer|min:1|max:60',
            'noofdoors' => 'required|integer|min:2|max:6',
            'automaticoption' => 'required|in:Yes,No',
            'laggagesoption' => 'required|integer|min:0|max:20',
            'driversage' => 'required|integer|min:18|max:80',
            'carratings' => 'required|numeric|min:1|max:5',
            'description' => 'required|string|max:1000',
            'paymentoption' => 'required|string|max:60',
            'telphonenumber' => ['required', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'email' => 'required|email|max:190',
            'pswd' => 'required|string|min:8|max:100',
            'formFile' => self::PHOTO,
        ];
    }

    protected function validationAttributes(): array
    {
        return ['carname' => 'number plate', 'carcategory' => 'category', 'CarSpecs' => 'make and model', 'Carcompany' => 'company name',
            'Carlocation' => 'location', 'kampaladrive' => 'Kampala daily rate', 'upcountrydrive' => 'upcountry daily rate',
            'noofpassengers' => 'passengers', 'noofdoors' => 'doors', 'laggagesoption' => 'luggage', 'driversage' => 'minimum driver age',
            'carratings' => 'rating', 'paymentoption' => 'payment method', 'telphonenumber' => 'phone number', 'pswd' => 'password', 'formFile' => 'photo'];
    }

    public function submit(): void
    {
        $this->validate();
        $withCommission = fn ($v) => round((float) $v * (1 + self::COMMISSION), 2);

        RentalCarModel::create([
            'DriverName' => $this->Carcompany, 'Company' => $this->Carcompany, 'TaxiNo' => $this->carname,
            'Ratings' => $this->carratings, 'DriverAge' => $this->driversage, 'CarSpec' => $this->CarSpecs,
            'Repayments' => $this->paymentoption,
            'Price' => $withCommission($this->kampaladrive), 'PricePerDay' => $withCommission($this->upcountrydrive),
            'CurrentLocation' => $this->Carlocation, 'City' => $this->Carlocation,
            'EmailAddress' => $this->email, 'TelephoneNumber' => $this->telphonenumber,
            // Driver passwords are checked by the separate driver app, which uses MD5.
            'Password' => md5($this->pswd),
            'Passengers' => $this->noofpassengers, 'Laggages' => $this->laggagesoption, 'Automatic' => $this->automaticoption,
            'Doors' => $this->noofdoors, 'Description' => $this->description, 'CarCategory' => $this->carcategory,
            'SearchTerms' => trim("{$this->CarSpecs} {$this->carcategory} {$this->Carlocation} {$this->Carcompany}"),
        ]);
        $this->storePhoto('AndroidDriver/FileUpload', $this->Carcompany . $this->carname);

        $this->reset('pswd');
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.register-cars')->layout('components.layouts.app', ['title' => 'Register a car']);
    }
}
