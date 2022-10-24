<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\RentalCarModel;
use Livewire\WithFileUploads;
use Livewire\Component;

class RegisterCars extends Component
{
    use WithFileUploads;
    public $carname;
    public $carcategory;
    public $CarSpecs;
    public $carratings;
    public $driversage;
    public $Carcompany;
    public $kampaladrive;
    public $upcountrydrive;
    public $noofpassengers;
    public $noofdoors;
    public $automaticoption;
    public $laggagesoption;
    public $Carlocation;
    public $formFile;
    public $email;
    public $pswd;
    public $description;
    public $paymentoption;
    public $telphonenumber;
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'carname' => 'required',
            'carcategory' => 'required',
            'CarSpecs' => 'required',
            'carratings' => 'required',
            'driversage' => 'required',
            'Carcompany'=>'required',
            'kampaladrive'=>'required',
            'upcountrydrive' => 'required',
            'noofpassengers' => 'required',
            'noofdoors' => 'required',
            'automaticoption' => 'required',
            'laggagesoption'=>'required',
            'Carlocation'=>'required',
            'description' => 'required',
            'paymentoption' => 'required',
            'telphonenumber' => 'required',
            'pswd' => 'required|min:5',
            'email' => 'required|email',
            'formFile' => 'required|image|mimes:jpeg,png,svg,jpg',
        ]);
    }
    public function submit()
    {
       
       $data = $this->validate([
            'carname' => 'required',
            'carcategory' => 'required',
            'CarSpecs' => 'required',
            'carratings' => 'required',
            'driversage' => 'required',
            'Carcompany'=>'required',
            'kampaladrive'=>'required',
            'upcountrydrive' => 'required',
            'noofpassengers' => 'required',
            'noofdoors' => 'required',
            'automaticoption' => 'required',
            'laggagesoption'=>'required',
            'Carlocation'=>'required',
            'description' => 'required',
            'paymentoption' => 'required',
            'telphonenumber' => 'required',
            'pswd' => 'required|min:5',
            'email' => 'required|email',
            'formFile' => 'required|image|mimes:jpeg,png,svg,jpg',
        ]);
        $RegisterCarModel= new RentalCarModel();
        $RegisterCarModel->DriverName = $this->Carcompany;
        $RegisterCarModel->TaxiNo = $this->carname;
        $RegisterCarModel->Company = $this->Carcompany;
        $RegisterCarModel->Ratings = $this->carratings;
        $RegisterCarModel->DriverAge = $this->driversage;
        $RegisterCarModel->CarSpec = $this->CarSpecs;
        $RegisterCarModel->Repayments = $this->paymentoption;
        $RegisterCarModel->Price = ($this->kampaladrive+(0.05*$this->kampaladrive));
        $RegisterCarModel->PricePerDay = ($this->upcountrydrive+(0.05*$this->upcountrydrive));
        $RegisterCarModel->CurrentLocation = $this->Carlocation;
        $RegisterCarModel->EmailAddress = $this->email;
        $RegisterCarModel->TelephoneNumber = $this->telphonenumber;
        $RegisterCarModel->Password = md5($this->pswd);
        $RegisterCarModel->Passengers = $this->noofpassengers;
        $RegisterCarModel->Laggages  = $this->laggagesoption;
        $RegisterCarModel->Automatic = $this->automaticoption;
        $RegisterCarModel->Doors = $this->noofdoors;
        $RegisterCarModel->Description = $this->description;
        $RegisterCarModel->CarCategory = $this->carcategory;
        $RegisterCarModel->save();
        $newfilename=$this->Carcompany.$this->carname.'.jpg';
        $this->formFile->storeAs('AndroidDriver/FileUpload/', $newfilename);
        $this->reset();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Car Registered Successfully!. Please wait for confirmation and response from Panobooking']);    
    }
    public function render()
    {
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.register-cars',['topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basessss');
    }
}
