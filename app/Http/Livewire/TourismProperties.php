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

class TourismProperties extends Component
{   public $clientname;
    public $clientemail;
    public $clientjob;
    public $clientcompany;
    public $clientfeedback;
    public $currencytype;
    public $currenyvalue;
    public $newsearchslug;
    public $newcityslug;
    public $newsiteslug;
    public function updated($fields){
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
            return redirect()->to('/Properties?searchslug='.$this->newsearchslug.'&sitename='.$this->newsiteslug.'&cityslug='.$this->newcityslug);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
    }
    public function mount(){
        $this->newsearchslug= request()->searchslug;
        $this->newsiteslug= request()->siteslug;
        $this->newcityslug= request()->cityslug;
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
        $searchname = $request->input('searchslug');
        $sitename = $request->input('siteslug');
        $hotelsearched=HotelsModel::where('Approved','Approved')->where('City',$cityname)->orWhere('SearchTerms','LIKE',"%$searchname%")->orWhere('SearchTerms','LIKE',"%$sitename%")->orWhere('SearchTerms','LIKE',"%$cityname%")->get();
        $carsearched=RentalCarModel::where('Approved','Approved')->where('City',$cityname)->orWhere('SearchTerms','LIKE',"%$searchname%")->orWhere('SearchTerms','LIKE',"%$sitename%")->orWhere('SearchTerms','LIKE',"%$cityname%")->get();
        $taxisearched=TaxiModel::Where('CurrentLocation', 'like',"%$cityname%")->orWhere('SearchTerms','LIKE',"%$searchname%")->orWhere('SearchTerms','LIKE',"%$sitename%")->orWhere('CurrentLocation', 'like',"%Entebbe%")->orWhere('CurrentLocation', 'like',"%Airport%")->get();
        $ambulancesearched=AmbulanceModel::where('Approved','Approved')->where('City',$cityname)->orWhere('SearchTerms','LIKE',"%$searchname%")->orWhere('SearchTerms','LIKE',"%$sitename%")->orWhere('SearchTerms','LIKE',"%$cityname%")->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        return view('livewire.tourism-properties',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'hotelsearched'=>$hotelsearched,'carsearched'=>$carsearched,'taxisearched'=>$taxisearched,'ambulancesearched'=>$ambulancesearched])->layout('layouts.base');
    }
}
