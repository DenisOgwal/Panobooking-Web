<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TaxiModel;
use Illuminate\Http\Request;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class TaxiSearch extends Component
{
    public $currencytype;
    public $currenyvalue;
    public $pickuppoint;
    public $pickuplocation;
    public $pickuplocations;
    public function mount(){
        $this->sorting="default";
        $this->pagesize=24;
        $this->pickuppoint= request()->pickuppoint;
        $this->pickuplocation = request()->Destination;
        $this->pickuplocations = request()->airport;
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
    public function updated(){
       
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
            return redirect()->route('TaxiSearch',['pickuppoint'=>  $this->pickuppoint,'Destination'=>   $this->pickuplocation,'airport'=> $this->pickuplocations]);
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
        $pickuppoint= $request->input('pickuppoint');
        $pickuplocation = $request->input('Destination');
        $pickuplocations = $request->input('airport');
        if($pickuppoint=="From Airport"){
        $taxisearched=TaxiModel::where('CurrentLocation', 'like',"%$pickuplocations%")->paginate(24);
        }else{
            $taxisearcheds=TaxiModel::where('CurrentLocation', 'like',"%$pickuplocation%")->paginate(24);
            if($taxisearcheds->count() > 0){
                $taxisearched=TaxiModel::where('CurrentLocation', 'like',"%$pickuplocation%")->paginate(24);
            }else{
                $taxisearched=TaxiModel::paginate(24);
            }
        }
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        return view('livewire.taxi-search',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->with('taxisearched',$taxisearched)->layout('layouts.base');
    }
}
