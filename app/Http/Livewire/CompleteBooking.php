<?php

namespace App\Http\Livewire;
use App\Models\BlogModel;
use App\Models\CartModel;
use App\Models\HotelsModel;
use Illuminate\Http\Request;
use App\Models\RoomsModel;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CompleteBooking extends Component
{
    public $hotelfinalslug;
    Public $Norooms;
    public $DestinationPrice;
    public $DestinationPrices;
    public $Destinationquantity;
    public $email;
    public $hotelemail;
    public $fullnames;
    public $checkoutdate;
    public $checkindate;
    public $result;
    public $Childrenno;
    public $roomprice;
    public $roomnoss;
    public $facilityname;
    public $roomname;
    public $mynotesbook;
    public $roomids;
    public $currencytype;
    public $currenyvalue;
    //public $BookingDescription;
    protected $casts = [
        'checkoutdate' => 'date:Y-m-d',
        'checkindate' => 'date:Y-m-d',
    ];
    public function mount(Request $request){
        $this->checkoutdate = now();
        $this->checkindate = now()->addYear();
        $this->roomids = $request->input('hotelfinalslug');
        $this->roomprice=RoomsModel::where('IDs',$this->roomids)->value('Price');
        $this->roomnoss=RoomsModel::where('IDs',$this->roomids)->value('NoOfRooms');
        $this->facilityname=RoomsModel::where('IDs',$this->roomids)->value('HotelName');
        $this->hotelemail=HotelsModel::where('Place',$this->facilityname)->value('EmailAddress');
        $this->roomname=RoomsModel::where('IDs',$this->roomids)->value('RoomName');
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
    $this->checkoutdate = $this->checkindate->addYear();
    }
    public function updated(){
        $start_time = \Carbon\Carbon::parse($this->checkindate);
        $finish_time = \Carbon\Carbon::parse($this->checkoutdate);
        $this->result = $start_time->diffInDays($finish_time, false);
        
        
        if($this->Destinationquantity==""){
            $this->DestinationPrice=$this->result;
            $this->DestinationPrices=$this->result;
        }else{
            if($this->roomnoss < $this->Destinationquantity){
                $this->reset('Destinationquantity');
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' =>'The Number of rooms Entered are more than those Available']);
            }
            if ($this->result > 0) {
                $this->DestinationPrices=(int)$this->Destinationquantity*(int)$this->result*(int)$this->roomprice;
                $this->DestinationPrice= (int)$this->Destinationquantity*(int)$this->result*ceil((((int)ceil($this->roomprice)) / $this->currenyvalue));
            }
        }
    }
    public function submitfeedback(Request $request){
        $this->validate([
            'checkoutdate' => 'required',
            'checkindate' => 'required',
            'Destinationquantity' => 'required',
            //'BookingDescription'=>'min:0',
        ]);
        if($request->session()->has('pano_client_data')){
            $cartmodel= new CartModel();
            $cartmodel->Facility = $this->facilityname;
            $cartmodel->ProductName = $this->roomname;
            $cartmodel->User = $this->email;
            $cartmodel->UserNames = $this->fullnames;
            $cartmodel->Quantity = $this->Destinationquantity;
            $cartmodel->Cost = $this->DestinationPrices;
            $cartmodel->OrderCode = 'N/A';
            $cartmodel->DatesFrom = $this->checkindate;
            $cartmodel->DatesTo = $this->checkoutdate;
            $cartmodel->BookingType = "Stays";
            $cartmodel->Currency = $this->currencytype;
            $cartmodel->CurrencyAmount  = ceil($this->DestinationPrice);
            $cartmodel->Descriptions ='Has '.$this->Childrenno.' Children';//. $this->BookingDescription;
            $cartmodel->save();
            $this->mynotesbook='Please Reserve for '.$this->fullnames.' '.$this->Destinationquantity.' '.$this->roomname.' Rooms for a Period: From '.$this->checkindate.'. To: '.$this->checkoutdate.'. TOTAL PAYABLE: '.$this->currencytype.' '.$this->DestinationPrice.'. Client Has '.$this->Childrenno.' Children';// Booking Notes: '. $this->BookingDescription;
            $mynotesbooks = urlencode ( $this->mynotesbook );
            $sites='https://www.panobooking.com/SendEmails/SendHotel.php?hotelemail='.$this->hotelemail.'&bookingnotes='.$mynotesbooks;
            @fopen( $sites,"r");
            
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Booking Successful']);//Booking Successful
            return redirect()->to('/BookingSuccess');
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Booking Faied!']);  
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
        $roomid = $request->input('hotelfinalslug');
        $rooms=RoomsModel::where('IDs',$roomid)->with('roomdetails')->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        return view('livewire.complete-booking',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'rooms'=>$rooms])->layout('layouts.basessss');
    }
}
