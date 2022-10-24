<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\RoomsModel;
use App\Models\RoomDetailsModel;
use App\Models\CurrencySelect;
use App\Models\LoginModel;

class HotelSingle extends Component
{
    public $hotelslug;
    public $clientemail;
    public $password;
    public $newhotelslug;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
    $this->newhotelslug=request()->hotelslug;
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
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);    
    }
    public function updatedCurrencytype(){
        $data =$this->validate([
            'currencytype'=>'required|min:1',
        ]);
        LoginModel::where('Email',session()->get('pano_client_data.Email'))->update(array('SelectedCurrency' => $this->currencytype));
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
            return redirect()->route('HotelSingle',['hotelslug'=> $this->newhotelslug]);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }     
    }
    public function signin(Request $request)
    {
       
       $this->validate([
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $login=LoginModel::where('Email',$this->clientemail)->where('ApprovalStatus', 'Approved');
        $collection=$login;
        if($login->first()){
             $plucked = $collection->pluck('Password')->first();
            if($plucked==md5($this->password)){
            $request->session()->put('pano_client_data',$login->first());
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'User Login Successfully!']);
            //redirect()->getUrlGenerator()->previous();
            return redirect()->route('HotelSingle',['hotelslug'=> $this->newhotelslug]);//->to('/ambulance');
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' =>'Login Password Does not Match Registered Password']);  
                return redirect()->route('HotelSingle',['hotelslug'=> $this->newhotelslug]);
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Account Does not Exist OR Account Not Approved!']);  
            return redirect()->route('HotelSingle',['hotelslug'=> $this->newhotelslug]);
        }
        //dd($this->name,$this->email,$this->password,$this->phonenum,'Progress');
    }
    public function render(Request $request)
    {
        $pickuplocation = $request->input('hotelslug');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $hotels=HotelsModel::where('IDs',$pickuplocation)->with('roomwith','popularamenities','propertydescription','bathroomdetail','bedroomdetail','viewdetail','outdoordetail','mediaandtechdetail','fooddetail','parkingdetail','transportationdetail','cleaningdetail','businessfacilitydetail','safetyandsecuritydetail','spadetail','kitchendetail','accessibilitydetail','houserulesdetail','childrenpolicydetail','childrenextrabeddetail','nearbyplacesdetail','topattractionsdetail','nearbyrestaurantsdetail','closestairportdetail','publictransportdetail','naturalbeautydetail','nearbymarketsdetail','nearbysupermarketsdetail','reasonsforstaydetail','restaurantsonsitedetail')->get();

        $collection=$hotels;
        $plucked = $collection->pluck('Place');
        $plucked2 = $collection->pluck('PlaceType');
        //$rooms=RoomsModel::where('HotelName',$plucked)->get();
        $rooms=RoomsModel::where('HotelName',$plucked)->with('roomdetails')->get();
        $hotelCat=HotelsModel::where('PlaceType',$plucked2)->get();
        
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        
       /* $collections=$rooms;
        $plucked2 = $collections->pluck('RoomName');
        $roomfeatures=RoomDetailsModel::where('RoomName',$plucked2)->get();
        'roomfeatures'=>$roomfeatures,*/
        return view('livewire.hotel-single',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'hotelCat'=>$hotelCat,'rooms'=>$rooms,'hotels'=>$hotels,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
