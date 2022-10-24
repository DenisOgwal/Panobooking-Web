<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use Livewire\Component;
use App\Models\BlogModel;
use App\Models\CarIncludes;
use App\Models\RentalCarModel;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
use Illuminate\Http\Request;
class CarRentalDetails extends Component
{
    public $carslug;
    public $clientemail;
    public $password;
    public $newcarslug;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
    $this->newcarslug=request()->carslug;
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
            return redirect()->route('CarDetails',['carslug'=> $this->newcarslug]);
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
            return redirect()->route('CarDetails',['carslug'=> $this->newcarslug]);//->to('/ambulance');
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' =>'Login Password Does not Match Registered Password']);  
                return redirect()->route('CarDetails',['carslug'=> $this->newcarslug]);
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Account Does not Exist OR Account Not Approved!']);  
            return redirect()->route('CarDetails',['carslug'=> $this->newcarslug]);
        }
        //dd($this->name,$this->email,$this->password,$this->phonenum,'Progress');
    }
    public function render(Request $request)
    {
        $carnumber = $request->input('carslug');
        $cars=RentalCarModel::where('IDs',$carnumber)->get();
        $collection=$cars;
        $plucked = $collection->pluck('DriverName');
        $plucked1 = $collection->pluck('TaxiNo');
        $plucked2 = $collection->pluck('CarCategory');
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $carCat=RentalCarModel::where('CarCategory',$plucked2)->get();
        $carincludes=CarIncludes::where('Car',$plucked1)->where('Company',$plucked)->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.car-rental-details',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carCat'=>$carCat,'carincludes'=>$carincludes,'cars'=>$cars,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
