<?php

namespace App\Http\Livewire;
use App\Models\CartModel;
use App\Models\HomeGuestModel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\BlogModel;
use App\Models\HotelsModel;
use Illuminate\Support\Facades\Mail;

class CompleteHomeGuestBooking extends Component
{
    public $homeguestfinalslug;
    Public $Norooms;
    public $DestinationPrice;
    public $email;
    public $fullnames;
    public $checkoutdate;
    public $checkindate;
    public $result;
    public $Childrenno;
    public $roomprice;
    public $roomnoss;
    public $facilityname;
    public $roomname;
    public $hotelemail;
    public $BookingDescription;
    protected $casts = [
        'checkoutdate' => 'date:Y-m-d',
        'checkindate' => 'date:Y-m-d',
    ];
    public function mount(Request $request){
        $this->checkoutdate = now();
        $this->checkindate = now()->addYear();
        $roomids = $request->input('homeguestfinalslug');
        $this->roomprice=HomeGuestModel::where('IDs',$roomids)->value('Prices');
        $this->facilityname=HomeGuestModel::where('IDs',$roomids)->value('GuestHouseName');
        $this->roomname=HomeGuestModel::where('IDs',$roomids)->value('GuestHouseName');
        $this->hotelemail=HomeGuestModel::where('GuestHouseName',$this->facilityname)->value('EmailAddress');
        $this->email=session()->get('pano_client_data.Email');
        $this->fullnames=session()->get('pano_client_data.FullNames');
    }
    public function updatedReminder()
    {
    $this->checkoutdate = $this->checkindate->addYear();
    }
    public function updated(){
        $start_time = \Carbon\Carbon::parse($this->checkindate);
        $finish_time = \Carbon\Carbon::parse($this->checkoutdate);
        $this->result = $start_time->diffInDays($finish_time, false);
        
        
            if ($this->result >= 0) {
        $this->DestinationPrice=(int)$this->result*(int)$this->roomprice;
            }
    }
    public function submitfeedback(Request $request){
        $this->validate([
            'checkoutdate' => 'required',
            'checkindate' => 'required',
            'BookingDescription'=>'required',
        ]);
        if($request->session()->has('pano_client_data')){
            $cartmodel= new CartModel();
            $cartmodel->Facility = $this->facilityname;
            $cartmodel->ProductName = $this->roomname;
            $cartmodel->User = $this->email;
            $cartmodel->UserNames = $this->fullnames;
            $cartmodel->Quantity = 1;
            $cartmodel->Cost = $this->DestinationPrice;
            $cartmodel->OrderCode = 'N/A';
            $cartmodel->DatesFrom = $this->checkindate;
            $cartmodel->DatesTo = $this->checkoutdate;
            $cartmodel->BookingType = "Home Guest House";
            $cartmodel->Descriptions ='Has '.$this->Childrenno.' Children, '. $this->BookingDescription;
            $cartmodel->save();
            /*if($this-> isOnline()){
                $mail_data=[
                    'recipient'=>$this->hotelemail,
                    'fromEmail'=>'sales.panobooking@gmail.com',
                    'fromName'=>'PANOBOOKING SALES',
                    'Subject'=>'Accomodation Booking',
                    'body'=>'Please Reserve for '.$this->fullnames.'. For a Period: From '.$this->checkindate.'. To: '.$this->checkoutdate.'. TOTAL PAYABLE: '.$this->DestinationPrice.'. Client Has '.$this->Childrenno.' Children, Booking Notes: '. $this->BookingDescription,
                ];
                Mail::send('layouts.booking_email_template',$mail_data, function($message) use($mail_data){
                    $message->to($this->hotelemail)
                    ->from('sales.panobooking@gmail.com' ,'PANOBOOKING SALES')
                    ->subject('Accomodation Booking');
                });
            }else{
                session()->flash('success','email sent');
                return redirect()->back()->withInput()->with('error','Check your internet connection');
            }*/
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Successfully Booked!']);
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
        $roomid = $request->input('homeguestfinalslug');
        $rooms=HomeGuestModel::where('IDs',$roomid)->with('roomdetails')->get();
        return view('livewire.complete-home-guest-booking',['topproperties'=>$topproperties,'topblogs'=>$topblogs,'rooms'=>$rooms])->layout('layouts.basessss');
    }
}
