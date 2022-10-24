<?php

namespace App\Http\Livewire;

use App\Models\AmbulanceModel;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegisterAmbulance extends Component
{
    use WithFileUploads;
    public $carname;
    public $carcategory;
    public $drivername;
    public $paymentoption;
    public $Carlocation;
    public $formFile;
    public $email;
    public $telphonenumber;
    public $pswd;
    public $description;
    
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'carname' => 'required',
            'carcategory' => 'required',
            'drivername' => 'required',
            'paymentoption' => 'required',
            'Carlocation' => 'required',
            'description' => 'required',
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
        'drivername' => 'required',
        'paymentoption' => 'required',
        'Carlocation' => 'required',
        'description' => 'required',
        'telphonenumber' => 'required',
        'pswd' => 'required|min:5',
        'email' => 'required|email',
        'formFile' => 'required|image|mimes:jpeg,png,svg,jpg',
        ]);
        $ambulanceModel= new AmbulanceModel();
        $ambulanceModel->DriverName = $this->drivername;
        $ambulanceModel->TaxiNo = $this->carname;
        $ambulanceModel->AmbulanceCategory = $this->carcategory;
        $ambulanceModel->Repayments  = $this->paymentoption;
        $ambulanceModel->CurrentLocation = $this->Carlocation;
        $ambulanceModel->CarSpec = $this->description;
        $ambulanceModel->Password = md5($this->pswd);
        $ambulanceModel->EmailAddress = $this->email;
        $ambulanceModel->TelephoneNumber =$this->telphonenumber;
        $ambulanceModel->save();
        $newfilename=$this->carname.'.jpg';
        $this->formFile->storeAs('AndroidDriver/FileUpload/', $newfilename);
        $this->reset();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Ambulance Registered Successfully!. Please wait for confirmation and response from Panobooking']);    
    }
    public function render()
    {
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.register-ambulance',['topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basessss');
    }
}
