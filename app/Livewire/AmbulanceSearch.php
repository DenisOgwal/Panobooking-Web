<?php

namespace App\Livewire;
use Livewire\Attributes\Url;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\AmbulanceModel;
use Livewire\WithPagination;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class AmbulanceSearch extends Component
{
    #[Url(as: 'Area')]
    public $url_Area;
    //use WithPagination;
    public $currencytype;
    public $currenyvalue;
    public $pickarea;
    public function mount(Request $request){
        $this->pickarea=$request->input('Area');
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
            $this->dispatch('alert', type: 'success',  message: 'Please Login to Change Currency!');
            return redirect()->to('/Login');
        }
        return redirect()->to('/AmbulanceSearch?Area='.$this->pickarea);
        }
    public function render()
    {
        $request = request();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $pickupArea = $this->url_Area;
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $ambulancesearched=AmbulanceModel::approved()->search($pickupArea, ['CurrentLocation', 'City'])->paginate(24);
        return view('livewire.ambulance-search',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->with('ambulancesearched',$ambulancesearched)->layout('layouts.base');
    }
}
