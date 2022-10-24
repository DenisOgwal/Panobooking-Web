<?php

namespace App\Http\Livewire;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
use App\Models\BlogModel;
use App\Models\HotelsModel;
use Illuminate\Http\Request;
use Livewire\Component;

class PropertyTypes extends Component
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
            return redirect()->to('/PropertyTypes');
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
        $propertytype=$request->input('propertyslug');
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        $hotelsearched=HotelsModel::where('PlaceType', $propertytype)->where('Approved','Approved')->paginate(24);
        $hotelsfeatured=HotelsModel::where('Featured','Yes')->where('PlaceType', $propertytype)->where('Approved','Approved')->orderBy('FaturedRank','ASC')->get();
        $hotels=HotelsModel::where('Trending','Yes')->where('PlaceType', $propertytype)->where('Approved','Approved')->orderBy('TrendRank','ASC')->get();
        return view('livewire.property-types',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'hotelsearched'=>$hotelsearched,'hotelsfeatured'=>$hotelsfeatured,'hotels'=>$hotels])->layout('layouts.base');
    }
}
