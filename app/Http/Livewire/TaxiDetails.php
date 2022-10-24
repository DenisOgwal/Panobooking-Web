<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BlogModel;
use App\Models\HotelsModel;
use App\Models\CarIncludes;
use App\Models\TaxiModel;
use App\Models\TaxiTripModel;
use Illuminate\Http\Request;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class TaxiDetails extends Component
{
    public $taxislug;
    public $clientemail;
    public $password;
    public $newtaxislug;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
    $this->newtaxislug=request()->taxislug;
    $this->datas=TaxiTripModel::all()->toArray();
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
            return redirect()->route('TaxiDetails',['taxislug'=> $this->newtaxislug]);
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
            return redirect()->route('TaxiDetails',['taxislug'=> $this->newtaxislug]);//->to('/ambulance');
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' =>'Login Password Does not Match Registered Password']);  
                return redirect()->route('TaxiDetails',['taxislug'=> $this->newtaxislug]);
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Account Does not Exist OR Account Not Approved!']);  
            return redirect()->route('TaxiDetails',['taxislug'=> $this->newtaxislug]);
        }
        //dd($this->name,$this->email,$this->password,$this->phonenum,'Progress');
    }
    public function render(Request $request)
    {
        $carnumber = $request->input('taxislug');
        $cars=TaxiModel::where('IDs',$carnumber)->get();
        $collection=$cars;
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $plucked = $collection->pluck('DriverName');
        $plucked1 = $collection->pluck('TaxiNo');
        $plucked2 = $collection->pluck('CarCategory');
        $carCat=TaxiModel::where('CarCategory',$plucked2)->get();
        $carincludes=CarIncludes::where('Car',$plucked1)->where('Company',$plucked)->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.taxi-details',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carCat'=>$carCat,'carincludes'=>$carincludes,'cars'=>$cars,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
