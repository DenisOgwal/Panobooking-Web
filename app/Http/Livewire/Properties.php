<?php

namespace App\Http\Livewire;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
use App\Models\BlogModel;
use Illuminate\Http\Request;
use App\Models\DestinationsModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;
use App\Models\AmbulanceModel;
use Livewire\Component;

class Properties extends Component
{
    public $clientname;
    public $clientemail;
    public $clientjob;
    public $clientcompany;
    public $clientfeedback;
    public $currencytype;
    public $currenyvalue;
    public function updated($fields){
        $this->validateOnly($fields,[
            'clientname'=>'required|min:6',
            'clientemail'=>'required|email',
            'clientcompany'=>'required',
            'clientfeedback'=>'required|min:20',
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
            return redirect()->to('/Properties');
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }    
    }
    public function mount(){
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
    public function render(Request $request)
    {
        $cityname = $request->input('cityslug');
        $countryname = $request->input('countryslug');
        $hotelsearched=HotelsModel::where('Approved','Approved')->where('City',$cityname)->Where('Country',$countryname)->get();
        $carsearched=RentalCarModel::where('Approved','Approved')->where('City',$cityname)->get();
        $taxisearched=TaxiModel::Where('CurrentLocation', 'like',"%$cityname%")->orwhere('SearchTerms', 'LIKE',"%$cityname%")->get();
        $ambulancesearched=AmbulanceModel::where('Approved','Approved')->where('City',$cityname)->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        return view('livewire.properties',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'hotelsearched'=>$hotelsearched,'carsearched'=>$carsearched,'taxisearched'=>$taxisearched,'ambulancesearched'=>$ambulancesearched])->layout('layouts.base');
    }
}
