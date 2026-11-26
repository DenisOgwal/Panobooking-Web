<?php

namespace App\Livewire;
use App\Support\CustomerAuth;
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
    public function signin()
    {
        $this->validate([
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Previously this page skipped the ApprovalStatus check; CustomerAuth enforces it.
        $result = app(CustomerAuth::class)->attempt($this->clientemail, $this->password);
        $back = redirect()->route('HomeGuestSingle', ['homeguestslug' => $this->newhomeguestslug]);

        if ($result === CustomerAuth::RESULT_OK) {
            session()->flash('alert', ['type' => 'success', 'message' => 'Welcome back, ' . app(CustomerAuth::class)->name() . '!']);
            return $back;
        }

        session()->flash('alert', ['type' => 'error', 'message' => $result === CustomerAuth::RESULT_BAD_PASSWORD
            ? 'That password is incorrect.'
            : 'No approved account exists for that email address.']);
        return $back;
    }
    public function render()
    {
        $request = request();
        $pickuplocation = $this->url_homeguestslug;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HomeGuestModel::orderBy('IDs','DESC')->take(5)->get();
        $hotels=HomeGuestModel::where('IDs',$pickuplocation)->with('roomwith','popularamenities','propertydescription','bathroomdetail','bedroomdetail','viewdetail','outdoordetail','mediaandtechdetail','fooddetail','parkingdetail','transportationdetail','cleaningdetail','businessfacilitydetail','safetyandsecuritydetail','spadetail','kitchendetail','accessibilitydetail','houserulesdetail','childrenpolicydetail','childrenextrabeddetail','nearbyplacesdetail','topattractionsdetail','nearbyrestaurantsdetail','closestairportdetail','publictransportdetail','naturalbeautydetail','nearbymarketsdetail','nearbysupermarketsdetail','reasonsforstaydetail','restaurantsonsitedetail')->get();
        abort_if($hotels->isEmpty(), 404);
        $hotelCat=HomeGuestModel::where('IDs','!=',$pickuplocation)->orderBy('IDs','DESC')->limit(10)->get();
        return view('livewire.guest-house-details',['hotelCat'=>$hotelCat,'hotels'=>$hotels,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
