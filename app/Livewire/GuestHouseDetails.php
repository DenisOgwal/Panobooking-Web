<?php

namespace App\Livewire;
use Livewire\Attributes\Url;
use Illuminate\Http\Request;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\LoginModel;
use App\Models\HomeGuestModel;
use Livewire\Component;

class GuestHouseDetails extends Component
{
    #[Url(as: 'homeguestslug')]
    public $url_homeguestslug;
    public $email;
    public $name;
    public $phonenum;
    public $homeguestslug;
    public $clientemail;
    public $password;
    public $newhomeguestslug;
    public function mount(){
    $this->newhomeguestslug=request()->homeguestslug;
    }
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);
    }
    public function signin(Request $request)
    {
       
       $this->validate([
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $login=LoginModel::where('Email',$this->clientemail);
        $collection=$login;
        if($login->first()){
             $plucked = $collection->pluck('Password')->first();
            if($plucked==md5($this->password)){
            $request->session()->put('pano_client_data',$login->first());
            $this->dispatch('alert', type: 'success',  message: 'User Login Successfully!');
            //redirect()->getUrlGenerator()->previous();
            return redirect()->route('HomeGuestSingle',['homeguestslug'=> $this->newhomeguestslug]);//->to('/ambulance');
            }else{
                $this->dispatch('alert', type: 'error', message: 'Login Password Does not Match Registered Password');  
                return redirect()->route('HomeGuestSingle',['homeguestslug'=> $this->newhomeguestslug]);
            }
        }else{
            $this->dispatch('alert', type: 'error',  message: 'Account Does not Exist!');  
            return redirect()->route('HomeGuestSingle',['homeguestslug'=> $this->newhomeguestslug]);
        }
        //dd($this->name,$this->email,$this->password,$this->phonenum,'Progress');
    }
    public function render()
    {
        $request = request();
        $pickuplocation = $this->url_homeguestslug;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HomeGuestModel::orderBy('IDs','DESC')->take(5)->get();
        $hotels=HomeGuestModel::where('IDs',$pickuplocation)->with('roomwith','popularamenities','propertydescription','bathroomdetail','bedroomdetail','viewdetail','outdoordetail','mediaandtechdetail','fooddetail','parkingdetail','transportationdetail','cleaningdetail','businessfacilitydetail','safetyandsecuritydetail','spadetail','kitchendetail','accessibilitydetail','houserulesdetail','childrenpolicydetail','childrenextrabeddetail','nearbyplacesdetail','topattractionsdetail','nearbyrestaurantsdetail','closestairportdetail','publictransportdetail','naturalbeautydetail','nearbymarketsdetail','nearbysupermarketsdetail','reasonsforstaydetail','restaurantsonsitedetail')->get();
        $hotelCat=HomeGuestModel::orderBy('IDs','DESC')->get();
        return view('livewire.guest-house-details',['hotelCat'=>$hotelCat,'hotels'=>$hotels,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
