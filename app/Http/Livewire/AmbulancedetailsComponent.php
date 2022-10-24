<?php

namespace App\Http\Livewire;
use App\Models\AmbulanceModel;
use App\Models\BlogModel;
use App\Models\HotelsModel;
use Livewire\Component;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
use Illuminate\Http\Request;

class AmbulancedetailsComponent extends Component
{
    public $ambulanceslug;
    public $Area;
    public $pickuppoint;
    public $reason;
    public $bookingnotes;
    public $pricepayable;
    public $clientemail;
    public $password;
    public $newambulanceslug;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
    $this->newambulanceslug=request()->ambulanceslug;
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
            return redirect()->to('/AmbulanceDetails?ambulanceslug='.$this->newambulanceslug);
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
            return redirect()->route('AmbulanceDetails',['ambulanceslug'=> $this->newambulanceslug]);//->to('/ambulance');
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' =>'Login Password Does not Match Registered Password']);  
                return redirect()->route('AmbulanceDetails',['ambulanceslug'=> $this->newambulanceslug]);
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Account Does not Exist OR Account Not Approved!']);  
            return redirect()->route('AmbulanceDetails',['ambulanceslug'=> $this->newambulanceslug]);
        }
        //dd($this->name,$this->email,$this->password,$this->phonenum,'Progress');
    }
    public function render(Request $request)
    {
        $carnumber = $request->input('ambulanceslug');
        $cars=AmbulanceModel::where('IDs',$carnumber)->with('Ambulancedetails')->get();
        $collection=$cars;
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $plucked2 = $collection->pluck('AmbulanceCategory');
        $carCat=AmbulanceModel::where('AmbulanceCategory',$plucked2)->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.ambulancedetails-component',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carCat'=>$carCat,'cars'=>$cars,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
