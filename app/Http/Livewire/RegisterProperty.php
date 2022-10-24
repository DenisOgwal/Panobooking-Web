<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\RegisterPropertyModel;
use Livewire\WithFileUploads;
use Livewire\Component;

class RegisterProperty extends Component
{
    use WithFileUploads;
    public $country;
    public $city;
    public $region;
    public $place;
    public $placetype;
    public $leastprice;
    public $taxesinclusive;
    public $pricedescription;
    public $slogan;
    public $creditcard;
    public $exactlocation;
    public $bedandbreakfast;
    public $paymentoption;
    public $telephonenumber;
    public $email;
    public $ratings;
    public $formFile;
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'country' => 'required',
            'city' => 'required',
            'region' => 'required',
            'place' => 'required',
            'placetype'=>'required',
            'leastprice'=>'required',
            'taxesinclusive' => 'required',
            'pricedescription' => 'required',
            'slogan' => 'required',
            'creditcard' => 'required',
            'exactlocation'=>'required',
            'bedandbreakfast'=>'required',
            'paymentoption' => 'required',
            'telephonenumber' => 'required',
            'email' => 'required|email',
            'ratings' => 'required',
            'formFile' => 'required|image|mimes:jpeg,png,svg,jpg',
        ]);
    }
    public function submit()
    {
       
       $data = $this->validate([
        'country' => 'required',
        'city' => 'required',
        'region' => 'required',
        'place' => 'required',
        'placetype'=>'required',
        'leastprice'=>'required',
        'taxesinclusive' => 'required',
        'pricedescription' => 'required',
        'slogan' => 'required',
        'creditcard' => 'required',
        'exactlocation'=>'required',
        'bedandbreakfast'=>'required',
        'paymentoption' => 'required',
        'telephonenumber' => 'required',
        'email' => 'required|email',
        'ratings' => 'required',
        'formFile' => 'required|image|mimes:jpeg,png,svg,jpg',
        ]);
        $RegisterPropertyModel= new RegisterPropertyModel();
        $RegisterPropertyModel->Place = $this->place;
        $RegisterPropertyModel->PlaceType = $this->placetype;
        $RegisterPropertyModel->City = $this->city;
        $RegisterPropertyModel->Country = $this->country;
        $RegisterPropertyModel->Rating = $this->ratings;
        $RegisterPropertyModel->Comment = $this->slogan;
        $RegisterPropertyModel->Prices = $this->leastprice;
        $RegisterPropertyModel->PriceSpecifications = $this->pricedescription;
        $RegisterPropertyModel->TaxesInclusive = $this->taxesinclusive;
        $RegisterPropertyModel->Prepayment = $this->paymentoption;
        $RegisterPropertyModel->BedBreakFast = $this->bedandbreakfast;
        $RegisterPropertyModel->CreditCard = $this->creditcard;
        $RegisterPropertyModel->ExactLocation = $this->exactlocation;
        $RegisterPropertyModel->EmailAddress = $this->email;
        $RegisterPropertyModel->ContactNo = $this->telephonenumber;
        $RegisterPropertyModel->save();
        $newfilename=$this->place.'.jpg';
        $this->formFile->storeAs('AndroidFiles/DestinationImages', $newfilename);
        $this->reset();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Property Registered Successfully!. Please wait for confirmation and response from Panobooking']);    
    }
    public function render()
    {
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.register-property',['topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basessss');
    }
}
