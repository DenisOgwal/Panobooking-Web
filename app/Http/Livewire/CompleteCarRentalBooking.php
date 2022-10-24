<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Mail;
use App\Models\BlogModel;
use App\Models\CartModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class CompleteCarRentalBooking extends Component
{
    public $carrentalfinalslug;
    public $email;
    public $fullnames;
    public $facilityname;
    public $carno;
    public $driveloc;
    public $pickuppoint;
    public $Carsquantity;
    public $Duration;
    public $driveprice;
    public $driveprices;
    public $bookingnotes;
    public $checkindate;
    public $finish_time ;
    public $carprice;
    public $roomids;
    public $caremail;
    public $mynotesbook;
    public $currencytype;
    public $currenyvalue;
    public function mount(Request $request){
        $this->checkindate = now()->addYear();
        $this->roomids = $request->input('carrentalfinalslug');
        $this->facilityname=RentalCarModel::where('IDs',$this->roomids)->value('DriverName');
        $this->carprice=RentalCarModel::where('IDs',$this->roomids)->value('Price');
        $this->carno=RentalCarModel::where('IDs',$this->roomids)->value('TaxiNo');
        $this->caremail=RentalCarModel::where('IDs',$this->roomids)->value('EmailAddress');
        $this->email=session()->get('pano_client_data.Email');
        $this->fullnames=session()->get('pano_client_data.FullNames');
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

    public function updated($fields){
        $this->validateOnly($fields,[
            'pickuppoint'=>'required',
            'Carsquantity'=>'required',
            'Duration'=>'required',
            'bookingnotes'=>'required',
        ]);
        $start_time = \Carbon\Carbon::parse($this->checkindate);
        $this->finish_time =$start_time->addDays($this->Duration);
        if($this->driveloc=="Upcountry Drive"){
            $this->carprice=RentalCarModel::where('IDs',$this->roomids)->value('PricePerDay');
            //if($this->Carsquantity==""){
                $this->driveprices=$this->carprice;
                $this->driveprice=ceil($this->carprice/ $this->currenyvalue);
            /*}else{
                if ($this->Carsquantity > 0) {
                    $this->driveprice=(int)$this->carprice*(int)$this->Carsquantity*(int)$this->Duration;
                }
            }*/
        }else{
            $this->carprice=RentalCarModel::where('IDs',$this->roomids)->value('Price');
            //if($this->Carsquantity==""){
                $this->driveprices=$this->carprice;
                $this->driveprice=ceil($this->carprice/ $this->currenyvalue);
            /*}else{
                if ($this->Carsquantity > 0) {
                    $this->driveprice=(int)$this->carprice*(int)$this->Carsquantity*(int)$this->Carsquantity*(int)$this->Duration;
                }
            }*/
        }


        if($this->Carsquantity==""){
            $this->driveprices=$this->carprice;
            $this->driveprice=ceil($this->carprice/ $this->currenyvalue);
        }else{
            if ($this->Duration >= 0) {
                $this->driveprices=$this->carprice*(int)$this->Carsquantity*(int)$this->Duration;
                $this->driveprice=ceil($this->carprice/ $this->currenyvalue)*(int)$this->Carsquantity*(int)$this->Duration;
            }
        }
    }
public function Submitrentalcarrequest(Request $request){
    $data =$this->validate([
        'pickuppoint'=>'required',
        'Carsquantity'=>'required',
        'Duration'=>'required',
        'bookingnotes'=>'required',
    ]);
    if($request->session()->has('pano_client_data')){
        $cartmodel= new CartModel();
        $cartmodel->Facility = $this->facilityname;
        $cartmodel->ProductName = $this->carno;
        $cartmodel->User = $this->email;
        $cartmodel->UserNames = $this->fullnames;
        $cartmodel->Quantity = $this->Carsquantity;
        $cartmodel->Cost =$this->driveprices;
        $cartmodel->OrderCode = "N/A";
        $cartmodel->DatesFrom = $this->checkindate;
        $cartmodel->DatesTo = $this->finish_time;
        $cartmodel->BookingType = "Car Rental";
        $cartmodel->Currency = $this->currencytype;
        $cartmodel->CurrencyAmount  = ceil($this->driveprice);
        $cartmodel->Descriptions ='I will Pick the Car at '.$this->pickuppoint.'. NOTES: '.$this->bookingnotes;
        $cartmodel->save();
        $this->mynotesbook='Please Reserve for '.$this->fullnames.' '.$this->Carsquantity.' '.$this->carno.' for a Period: From '.$this->checkindate.'. To: '.$this->finish_time.'. TOTAL PAYABLE: '.$this->currencytype.' '.$this->driveprice.' I will Pick the Car at '.$this->pickuppoint.'. Booking Notes: '.$this->bookingnotes;
        $mynotesbooks = urlencode ( $this->mynotesbook );
        $sites='https://www.panobooking.com/SendEmails/SendCar.php?hotelemail='.$this->caremail.'&bookingnotes='.$mynotesbooks;
        @fopen( $sites,"r");
       
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Car Request Submitted, Wait for response in minimal Time possible']);
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
        $carnumber = $request->input('carrentalfinalslug');
        $cars=RentalCarModel::where('IDs',$carnumber)->get();
        return view('livewire.complete-car-rental-booking',['topproperties'=>$topproperties,'topblogs'=>$topblogs,'cars'=>$cars])->layout('layouts.basessss');
    }
}
