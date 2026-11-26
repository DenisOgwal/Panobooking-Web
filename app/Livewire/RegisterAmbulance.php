<?php

namespace App\Livewire;

use App\Livewire\Concerns\RegistersListing;
use App\Models\AmbulanceModel;
use Livewire\Component;

/** /RegisterAmbulance: list an ambulance (pending approval). */
class RegisterAmbulance extends Component
{
    use RegistersListing;

    public const CATEGORIES = ['Advanced Life Support', 'Basic Life Support', 'Patient Transport', 'Neonatal', 'Air Ambulance'];

    public string $carname = '';
    public string $carcategory = 'Basic Life Support';
    public string $drivername = '';
    public string $Carlocation = '';
    public string $description = '';
    public string $paymentoption = 'Cash';
    public string $telphonenumber = '';
    public string $email = '';
    public string $pswd = '';

    protected function rules(): array
    {
        return [
            'carname' => [...self::SAFE_NAME, 'unique:ambulances,TaxiNo'],
            'carcategory' => 'required|in:' . implode(',', self::CATEGORIES),
            'drivername' => 'required|string|max:120',
            'Carlocation' => 'required|string|max:120',
            'description' => 'required|string|max:200',
            'paymentoption' => 'required|string|max:60',
            'telphonenumber' => ['required', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'email' => 'required|email|max:190',
            'pswd' => 'required|string|min:8|max:100',
            'formFile' => self::PHOTO,
        ];
    }

    protected function validationAttributes(): array
    {
        return ['carname' => 'number plate', 'carcategory' => 'category', 'drivername' => 'operator name', 'Carlocation' => 'base location',
            'description' => 'vehicle', 'paymentoption' => 'payment method', 'telphonenumber' => 'phone number', 'pswd' => 'password', 'formFile' => 'photo'];
    }

    public function submit(): void
    {
        $this->validate();

        AmbulanceModel::create([
            'DriverName' => $this->drivername, 'TaxiNo' => $this->carname, 'AmbulanceCategory' => $this->carcategory,
            'Repayments' => $this->paymentoption, 'CurrentLocation' => $this->Carlocation, 'City' => $this->Carlocation,
            'CarSpec' => $this->description,
            // Driver passwords are checked by the separate driver app, which uses MD5.
            'Password' => md5($this->pswd),
            'EmailAddress' => $this->email, 'TelephoneNumber' => $this->telphonenumber,
            'SearchTerms' => trim("{$this->carcategory} ambulance {$this->Carlocation}"),
        ]);
        $this->storePhoto('AndroidDriver/FileUpload', $this->carname);

        $this->reset('pswd');
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.register-ambulance')->layout('components.layouts.app', ['title' => 'Add an ambulance']);
    }
}
