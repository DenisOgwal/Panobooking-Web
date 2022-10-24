<?php

namespace App\Http\Livewire;
use Illuminate\Http\Request;
use App\Models\AmbulanceModel;
use App\Models\BlogModel;
use App\Models\CartModel;
use App\Models\HotelsModel;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class CompleteAmbulanceBooking extends Component
{
    public $ambulancefinalslug;
    public $email;
    public $fullnames;
    public $facilityname;
    public $ambulanceno;
    public $Area;
    public $pickuppoint;
    public $reason;
    public $bookingnotes;
    public $checkindate;
    public $Pickuptime;
    public $caremail;
    public $mynotesbook;
    public function mount(Request $request){
        $this->checkindate = now()->addYear();
        $roomids = $request->input('ambulancefinalslug');
        $this->facilityname=AmbulanceModel::where('IDs',$roomids)->value('EmailAddress');
        $this->ambulanceno=AmbulanceModel::where('IDs',$roomids)->value('TaxiNo');
        $this->email=session()->get('pano_client_data.Email');
        $this->fullnames=session()->get('pano_client_data.FullNames');
        $this->caremail=AmbulanceModel::where('IDs',$roomids)->value('EmailAddress');
    }
    public function updatedReminder()
    {
    $this->checkindate->addYear();
    }

    public function updated($fields){
        $this->validateOnly($fields,[
            'Area'=>'required',
            'pickuppoint'=>'required',
            'reason'=>'required|min:10',
            'bookingnotes'=>'required|min:2',
        ]);
        
    }
public function submitaxirequest(Request $request){
    $data =$this->validate([
            'Area'=>'required',
            'pickuppoint'=>'required',
            'reason'=>'required|min:10',
            'bookingnotes'=>'required|min:2',
    ]);
    if($request->session()->has('pano_client_data')){
        $mytime = \Carbon\Carbon::now();
        
        $cartmodel= new CartModel();
        $cartmodel->Facility = $this->facilityname;
        $cartmodel->ProductName = $this->ambulanceno;
        $cartmodel->User = $this->email;
        $cartmodel->UserNames = $this->fullnames;
        $cartmodel->Quantity = 1;
        $cartmodel->Cost =0;
        $cartmodel->OrderCode = 'N/A';
        $cartmodel->DatesFrom = $this->checkindate .' '.$this->Pickuptime;
        $cartmodel->DatesTo = 'N/A';
        $cartmodel->BookingType = "Ambulance";
        $cartmodel->Descriptions ='Please Pick me up from '.$this->Area.'. EXACT PICKUPPOINT: '.$this->pickuppoint.'. REASON: '.$this->reason.'. NOTES: '.$this->bookingnotes;
        $cartmodel->save();
        $this->mynotesbook='Please Help '.$this->fullnames.', Ambulance No. '.$this->ambulanceno.'. Pickup Date: '.$this->checkindate .'. Pickup Time:  '.$this->Pickuptime.' '.'Please Pick me up from '.$this->Area.'. EXACT PICKUPPOINT: '.$this->pickuppoint.'. REASON: '.$this->reason.'. NOTES: '.$this->bookingnotes;
        $mynotesbooks = urlencode ( $this->mynotesbook );
        $sites='https://www.panobooking.com/SendEmails/SendAmbulance.php?hotelemail='.$this->caremail.'&bookingnotes='.$mynotesbooks;
        @fopen( $sites,"r");
        /*if($this-> isOnline()){
            $mail_data=[
                'recipient'=>$this->caremail,
                'fromEmail'=>'sales.panobooking@gmail.com',
                'fromName'=>'PANOBOOKING SALES',
                'Subject'=>'Ambulance Call',
                'body'=>'Please Help '.$this->fullnames.', Ambulance No. '.$this->ambulanceno.'. Pickup Date: '.$this->checkindate .'. Pickup Time:  '.$this->Pickuptime.' '.'Please Pick me up from '.$this->Area.'. EXACT PICKUPPOINT: '.$this->pickuppoint.'. REASON: '.$this->reason.'. NOTES: '.$this->bookingnotes,
            ];
            Mail::send('layouts.booking_email_template',$mail_data, function($message) use($mail_data){
                $message->to($this->caremail)
                ->from('sales.panobooking@gmail.com' ,'PANOBOOKING SALES')
                ->subject('Ambulance Call');
            });
        }else{
            session()->flash('success','email sent');
            return redirect()->back()->withInput()->with('error','Check your internet connection');
        }*/
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Ambulance Request Submitted, Wait for response in minimal Time possible']);
        return redirect()->to('/BookingSuccess');
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Call Faied!']);  
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
        $carnumber = $request->input('ambulancefinalslug');
        $cars=AmbulanceModel::where('IDs',$carnumber)->with('Ambulancedetails')->get();
        return view('livewire.complete-ambulance-booking',['topproperties'=>$topproperties,'topblogs'=>$topblogs,'cars'=>$cars])->layout('layouts.basessss');
    }
}
