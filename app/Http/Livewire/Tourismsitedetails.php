<?php

namespace App\Http\Livewire;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
use Illuminate\Http\Request;
use App\Models\BlogModel;
use App\Models\TourismSite;
use App\Models\HotelsModel;
use Livewire\Component;

class Tourismsitedetails extends Component
{
    public $newsearchslug;
    public $searchslug;
    public $clientname;
    public $clientemail;
    public $clientjob;
    public $clientcompany;
    public $clientfeedback;
    public $currencytype;
    public $currenyvalue;
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
            return redirect()->to('/TourismSiteDetails?searchslug='.$this->newsearchslug);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
        //return redirect()->to('/TourismSiteDetails');
    }
    public function mount(){
        $this->newsearchslug= request()->searchslug;
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
        $searchslugs = $request->input('searchslug');
        $tourismsites=TourismSite::where('ID',$searchslugs)->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $tourismsitess=TourismSite::orderBy('ID','ASC')->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.tourismsitedetails',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'tourismsites'=>$tourismsites,'tourismsitess'=>$tourismsitess])->layout('layouts.base');
    }
}
