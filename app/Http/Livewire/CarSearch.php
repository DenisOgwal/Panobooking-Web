<?php

namespace App\Http\Livewire;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\BlogModel;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class CarSearch extends Component
{
    public $currencytype;
    public $currenyvalue;
    public $pickarea;
    public function mount(Request $request){
        $this->pickarea=$request->input('pickuplocation');
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
    }else{
        $this->currencytype="UGX";
        $this->currenyvalue=1;
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
        return redirect()->to('/Login');
    }
    return redirect()->to('/CarSearch?pickuplocation='.$this->pickarea);
    }
    public function render(Request $request)
    {
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $pickuplocation = $request->input('pickuplocation');
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $carsearched=RentalCarModel::where('Approved','Approved')->where('CurrentLocation', 'like',"%$pickuplocation%")->orWhere('City', 'like',"%$pickuplocation%")->paginate(24);
        return view('livewire.car-search',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->with('carsearched',$carsearched)->layout('layouts.base');
    }
}
