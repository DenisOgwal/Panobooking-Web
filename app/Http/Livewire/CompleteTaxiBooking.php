<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Mail;
use App\Models\BlogModel;
use App\Models\CartModel;
use App\Models\HotelsModel;
use App\Models\TaxiModel;
use App\Models\TaxiTripModel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
use PhpParser\Builder\Function_;

class CompleteTaxiBooking extends Component
{
    public $taxifinalslug;
    public $email;
    public $fullnames;
    public $facilityname;
    public $carno;
    public $pickuppoint;
    public $pickupdescription;
    public $airportfield;
    public $Destination;
    public $Destinations;
    public $NoOfPeople;
    public $tripcost;
    public $datetime;
    public $bookingnotes;
    public $checkindate;
    public $roomids;
    public $datas;
    public $caremail;
    public $mynotesbook;
    public $currencytype;
    public $currenyvalue;
    public $tripcosts;
    public function mount(Request $request){
        $this->checkindate = now()->addYear();
        $this->roomids = $request->input('taxifinalslug');
        $this->facilityname=TaxiModel::where('IDs',$this->roomids)->value('DriverName');
        $this->carno=TaxiModel::where('IDs',$this->roomids)->value('TaxiNo');
        $this->email=session()->get('pano_client_data.Email');
        $this->caremail=TaxiModel::where('IDs',$this->roomids)->value('EmailAddress');
        $this->fullnames=session()->get('pano_client_data.FullNames');
        $this->datas=TaxiTripModel::where('Tos','LIKE','%'.$this->Destination.'%')->get()->toArray();
        if(session()->has('pano_client_data')){
            $login=LoginModel::where('Email',session()->get('pano_client_data.Email'));
            $collection=$login;
            $plucked = $collection->pluck('SelectedCurrency')->first();
            $this->currencytype=$plucked;
            if($this->currencytype=="UGX"){
                $this->currenyvalue=1;
            }else{
            $selectedcurrency=CurrencySelect::where('CurrencyName',$this->currencytype);
            $collections=$selectedcurrency;
            $plucks = $collections->pluck('CurrencyValue')->first();
            $this->currenyvalue=$plucks;
            }
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
           
        }
    }
    public function updatedReminder()
    {
    $this->checkindate->addYear();
    }
    public function updatedQuery(){

    }

    public function updated($fields){
        $this->validateOnly($fields,[
            'pickuppoint'=>'required',
            'airportfield'=>'required',
            'Destination'=>'required',
            'NoOfPeople'=>'required',
            'tripcost'=>'required',
            'bookingnotes'=>'required',
        ]);
        $this->tripcosts=TaxiTripModel::where('Tos',$this->Destination)->where('Froms',$this->airportfield)->Value('Price');
        $this->tripcost=ceil((TaxiTripModel::where('Tos',$this->Destination)->where('Froms',$this->airportfield)->Value('Price'))/$this->currenyvalue);
        if($this->pickuppoint=="From Airport"){
            $this->pickupdescription="Pick Me up from ".$this->airportfield." Airport to ".$this->Destination;
        }else{
            $this->pickupdescription="Pick Me up from ".$this->Destination." to ".$this->airportfield." Airport";
        }
    }

public function Submitaxirequest(Request $request){
    $data =$this->validate([
            'pickuppoint'=>'required',
            'airportfield'=>'required',
            'Destination'=>'required',
            'NoOfPeople'=>'required',
            'tripcost'=>'required',
            'bookingnotes'=>'required',
    ]);
    if($request->session()->has('pano_client_data')){
        $cartmodel= new CartModel();
        $cartmodel->Facility = $this->facilityname;
        $cartmodel->ProductName = $this->carno;
        $cartmodel->User = $this->email;
        $cartmodel->UserNames = $this->fullnames;
        $cartmodel->Quantity =1;
        $cartmodel->Cost =$this->tripcosts;
        $cartmodel->OrderCode = "N/A";
        $cartmodel->DatesFrom = $this->checkindate;
        $cartmodel->DatesTo = "N/A";
        $cartmodel->BookingType = "Airport Taxi";
        $cartmodel->Currency = $this->currencytype;
        $cartmodel->CurrencyAmount  = ceil($this->tripcost);
        $cartmodel->Descriptions =$this->pickupdescription.' NO. OF PEOPLE:'.$this->NoOfPeople.'. NOTES: '.$this->bookingnotes;
        $cartmodel->save();
        $this->mynotesbook='Please Reserve for '.$this->fullnames.' '.$this->carno.'. '.$this->pickupdescription.'. TOTAL PAYABLE: '.$this->currencytype.' '.$this->tripcost.' NO. OF PEOPLE:'.$this->NoOfPeople.'. NOTES: '.$this->bookingnotes;
        $mynotesbooks = urlencode ( $this->mynotesbook );
        $sites='https://www.panobooking.com/SendEmails/SendTaxi.php?hotelemail='.$this->caremail.'&bookingnotes='.$mynotesbooks;
        @fopen( $sites,"r");
        
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Taxi Request Submitted, Wait for response in minimal Time possible']);
        return redirect()->to('/BookingSuccess');
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Request Faied!']);  
        }


        }
    public function isOnline($site='https://www.youtube.com/'){
    if(@fopen($site,"r")){
        return true;
    }else{
        return false;
    }
    }
    public function render(Request $request)
    {
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $carnumber = $request->input('taxifinalslug');
        $cars=TaxiModel::where('IDs',$carnumber)->get();
        return view('livewire.complete-taxi-booking',['topproperties'=>$topproperties,'topblogs'=>$topblogs,'cars'=>$cars])->layout('layouts.basessss');
    }
}
