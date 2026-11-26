<?php

namespace App\Livewire;
use App\Support\CustomerAuth;
use Livewire\Attributes\Url;
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
    #[Url(as: 'hotelslug')]
    public $url_hotelslug;
    public $email;
    public $name;
    public $phonenum;
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
            $this->dispatch('alert', type: 'success',  message: 'Please Login to Change Currency!');
            return redirect()->to('/Login');
        }     
    }
    public function signin() {
       
       $this->validate([
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $result = app(CustomerAuth::class)->attempt($this->clientemail, $this->password);

        if ($result === CustomerAuth::RESULT_OK) {
            session()->flash('alert', ['type' => 'success', 'message' => 'Welcome back, ' . app(CustomerAuth::class)->name() . '!']);
            return redirect()->route('HotelSingle',['hotelslug'=> $this->newhotelslug]);
        }

        $message = $result === CustomerAuth::RESULT_BAD_PASSWORD
            ? 'That password is incorrect.'
            : 'No approved account exists for that email address.';
        session()->flash('alert', ['type' => 'error', 'message' => $message]);
        return redirect()->route('HotelSingle',['hotelslug'=> $this->newhotelslug]);
    }
    public function render()
    {
        $request = request();
        $pickuplocation = $this->url_hotelslug;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $hotels=HotelsModel::where('IDs',$pickuplocation)->with('roomwith','popularamenities','propertydescription','bathroomdetail','bedroomdetail','viewdetail','outdoordetail','mediaandtechdetail','fooddetail','parkingdetail','transportationdetail','cleaningdetail','businessfacilitydetail','safetyandsecuritydetail','spadetail','kitchendetail','accessibilitydetail','houserulesdetail','childrenpolicydetail','childrenextrabeddetail','nearbyplacesdetail','topattractionsdetail','nearbyrestaurantsdetail','closestairportdetail','publictransportdetail','naturalbeautydetail','nearbymarketsdetail','nearbysupermarketsdetail','reasonsforstaydetail','restaurantsonsitedetail')->get();
        abort_if($hotels->isEmpty(), 404);

        $collection=$hotels;
        $plucked = $collection->first()->Place;
        $plucked2 = $collection->first()->PlaceType;
        //$rooms=RoomsModel::where('HotelName',$plucked)->get();
        $rooms=RoomsModel::where('HotelName',$plucked)->with('roomdetails')->get();
        $hotelCat=HotelsModel::approved()->where('PlaceType',$plucked2)->where('IDs','!=',$pickuplocation)->limit(10)->get();
        
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        
       /* $collections=$rooms;
        $plucked2 = $collections->pluck('RoomName');
        $roomfeatures=RoomDetailsModel::where('RoomName',$plucked2)->get();
        'roomfeatures'=>$roomfeatures,*/
        return view('livewire.hotel-single',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'hotelCat'=>$hotelCat,'rooms'=>$rooms,'hotels'=>$hotels,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
