<?php

namespace App\Livewire;

use App\Listings\AmbulanceListing;
use App\Listings\ListingForm;
use App\Livewire\Concerns\RegistersListing;
use Livewire\Component;

/** /RegisterAmbulance: list an ambulance (pending approval; rules: App\Listings\AmbulanceListing). */
class RegisterAmbulance extends Component
{
    use RegistersListing;

    public const CATEGORIES = AmbulanceListing::CATEGORIES;

    public string $carname = '';
    public string $carcategory = 'Basic Life Support';
    public string $drivername = '';
    public string $Carlocation = '';
    public string $description = '';
    public string $paymentoption = 'Cash';
    public string $telphonenumber = '';
    public string $email = '';
    public string $pswd = '';

    protected function listingForm(): ListingForm { return app(AmbulanceListing::class); }

    protected function listingFields(): array
    {
        return [
            'plate' => 'carname', 'category' => 'carcategory', 'operator' => 'drivername', 'location' => 'Carlocation',
            'vehicle' => 'description', 'payment_method' => 'paymentoption', 'phone' => 'telphonenumber', 'email' => 'email',
            'driver_password' => 'pswd', 'photo' => 'formFile',
        ];
    }

    public function submit(): void
    {
        $this->submitListing();
        $this->reset('pswd');
    }

    public function render()
    {
        return view('livewire.register-ambulance')->layout('components.layouts.app', ['title' => 'Add an ambulance']);
    }
}
