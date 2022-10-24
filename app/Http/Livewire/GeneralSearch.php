<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use Livewire\Component;
use App\Models\BlogModel;
use Illuminate\Http\Request;
use App\Models\RentalCarModel;
use App\Models\TaxiModel;
use App\Models\AmbulanceModel;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
use App\Models\TourPackages;
use App\Models\TourismSite;
use App\Models\events as ModelsEvents;

class GeneralSearch extends Component
{
    public $currencytype;
    public $currenyvalue;
    public $pickarea;
    public function mount(Request $request){
        $this->pickarea=$request->input('salessearch');
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
            return redirect()->to('/generalSearchs?salessearch='.$this->pickarea);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
    
        }
    public function render(Request $request)
    {
        $salessearch = $request->input('salessearch');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $hotelsearched=HotelsModel::where('Approved','Approved')->where('SearchTerms', 'LIKE',"%$salessearch%")->orwhere('City', 'LIKE',"%$salessearch%")->orWhere('PlaceType', 'LIKE',"%$salessearch%")->orWhere('Place', 'LIKE',"%$salessearch%")->orderBy('FaturedRank','ASC')->get();
        $carsearched=RentalCarModel::where('Approved','Approved')->where('SearchTerms', 'LIKE',"%$salessearch%")->orWhere('CurrentLocation', 'like',"%$salessearch%")->orWhere('City', 'like',"%$salessearch%")->orWhere('DriverName', 'like',"%$salessearch%")->orWhere('TaxiNo', 'like',"%$salessearch%")->orWhere('Company', 'like',"%$salessearch%")->orWhere('CarCategory', 'like',"%$salessearch%")->orWhere('CarSpec', 'like',"%$salessearch%")->orderBy('updated_at','ASC')->get();
        $taxisearched=TaxiModel::Where('CurrentLocation', 'like',"%$salessearch%")->where('SearchTerms', 'LIKE',"%$salessearch%")->orWhere('DriverName', 'like',"%$salessearch%")->orWhere('TaxiNo', 'like',"%$salessearch%")->orWhere('CarCategory', 'like',"%$salessearch%")->orWhere('CarSpec', 'like',"%$salessearch%")->orWhere('CurrentLocation', 'like',"%Entebbe%")->orWhere('CurrentLocation', 'like',"%Airport%")->orderBy('updated_at','ASC')->get();
        $ambulancesearched=AmbulanceModel::where('Approved','Approved')->where('SearchTerms', 'LIKE',"%$salessearch%")->orwhere('CurrentLocation', 'like',"%$salessearch%")->orWhere('City', 'like',"%$salessearch%")->orWhere('DriverName', 'like',"%$salessearch%")->orWhere('TaxiNo', 'like',"%$salessearch%")->orWhere('AmbulanceCategory', 'like',"%$salessearch%")->orWhere('CarSpec', 'like',"%$salessearch%")->orderBy('updated_at','ASC')->get();
        $mytime = \Carbon\Carbon::now();
        $currentda=$mytime->toDateString();
        $tourpackagesearched=TourPackages::where('TravelDate','>',$currentda)->where('SearchTerms', 'LIKE',"%$salessearch%")->orwhere('PackageName', 'like',"%$salessearch%")->orWhere('City', 'like',"%$salessearch%")->orderBy('IDs','ASC')->get();
        $tourismsites=TourismSite::where('SearchKeyWords', 'LIKE',"%$salessearch%")->orwhere('SiteName', 'like',"%$salessearch%")->orWhere('City', 'like',"%$salessearch%")->orderBy('ID','ASC')->get();
        $eventss=ModelsEvents::where('EndDate','>=',$currentda)->where('SearchKeyWords', 'LIKE',"%$salessearch%")->orwhere('EventName', 'like',"%$salessearch%")->orWhere('City', 'like',"%$salessearch%")->orderBy('ID','ASC')->get();
        return view('livewire.general-search',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'hotelsearched'=>$hotelsearched,'carsearched'=>$carsearched,'taxisearched'=>$taxisearched,'ambulancesearched'=>$ambulancesearched,'tourpackagesearched'=>$tourpackagesearched,'tourismsites'=>$tourismsites,'eventss'=>$eventss])->layout('layouts.base');
    }
}
