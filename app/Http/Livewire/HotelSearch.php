<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\BlogModel;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class HotelSearch extends Component
{
    
    public $currencytype;
    public $currenyvalue;
    public $pickarea;
    public function mount(Request $request){
        $this->pickarea=$request->input('from_city');
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
            return redirect()->to('/HotelSearch?from_city='.$this->pickarea);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
       
        }
    public function render(Request $request)
    {
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        $from_city = $request->input('from_city');
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $hotelsearched=HotelsModel::where('City', 'LIKE',"%$from_city%")->where('Approved','Approved')->paginate(24);
        $hotelsfeatured=HotelsModel::where('Featured','Yes')->where('Approved','Approved')->orderBy('FaturedRank','ASC')->get();
        $hotels=HotelsModel::where('Trending','Yes')->where('Approved','Approved')->orderBy('TrendRank','ASC')->get();
        return view('livewire.hotel-search',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'hotelsfeatured'=>$hotelsfeatured,'hotels'=>$hotels,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'hotelsearched'=>$hotelsearched])->layout('layouts.base');
    }
    /*function fetch_data(Request $request){
        if($request->ajax()){
            $from_city = $request->input('from_city');
            $hotelsearched=HotelsModel::where('City', 'LIKE',"%$from_city%")->paginate(24);
            return view('livewire.hotelpagination',compact('hotelsearched'))->render();
        }
    }*/
}

