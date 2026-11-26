<?php

namespace App\Livewire;
use Livewire\Attributes\Url;
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
    #[Url(as: 'salessearch')]
    public $url_salessearch;
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
            $this->dispatch('alert', type: 'success',  message: 'Please Login to Change Currency!');
            return redirect()->to('/Login');
        }
    
        }
    public function render()
    {
        $request = request();
        $salessearch = $this->url_salessearch;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $hotelsearched=HotelsModel::approved()->search($salessearch, HotelsModel::SEARCH_COLUMNS)->orderBy('FaturedRank','ASC')->get();
        $carsearched=RentalCarModel::approved()->search($salessearch, RentalCarModel::SEARCH_COLUMNS)->orderBy('updated_at','ASC')->get();
        $taxisearched=TaxiModel::when(! TaxiModel::servesDestination($salessearch), fn ($q) => $q->search($salessearch, TaxiModel::SEARCH_COLUMNS))->orderBy('updated_at','ASC')->get();
        $ambulancesearched=AmbulanceModel::approved()->search($salessearch, AmbulanceModel::SEARCH_COLUMNS)->orderBy('updated_at','ASC')->get();
        $mytime = \Carbon\Carbon::now();
        $currentda=$mytime->toDateString();
        $tourpackagesearched=TourPackages::upcoming()->search($salessearch, TourPackages::SEARCH_COLUMNS)->orderBy('IDs','ASC')->get();
        $tourismsites=TourismSite::search($salessearch, TourismSite::SEARCH_COLUMNS)->orderBy('ID','ASC')->get();
        $eventss=ModelsEvents::upcoming()->search($salessearch, ModelsEvents::SEARCH_COLUMNS)->orderBy('ID','ASC')->get();
        return view('livewire.general-search',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'hotelsearched'=>$hotelsearched,'carsearched'=>$carsearched,'taxisearched'=>$taxisearched,'ambulancesearched'=>$ambulancesearched,'tourpackagesearched'=>$tourpackagesearched,'tourismsites'=>$tourismsites,'eventss'=>$eventss])->layout('layouts.base');
    }
}
