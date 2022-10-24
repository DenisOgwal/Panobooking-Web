<?php

namespace App\Http\Livewire;
use App\Models\BlogCommentCount;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\FeedBackModel;
use App\Models\DestinationsModel;
use Livewire\Component;
use App\Models\CurrencySelect;
use App\Models\LoginModel;

class HelpCenter extends Component
{
    public $currencytype;
    public $currenyvalue;
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
        return redirect()->to('/TermsAndConditions');
    }else{
        $this->currencytype="UGX";
        $this->currenyvalue=1;
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
        return redirect()->to('/Login');
    }
    
    }
    public function render()
    {
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $blogs=BlogModel::all();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $blogcomments=BlogCommentCount::all();
        $clientfeedbacks=FeedBackModel::all();
        $seconddestinations=DestinationsModel::orderBy('DestinationID','DESC')->skip(5)->take(5)->get();
        $firstdestinations=DestinationsModel::orderBy('DestinationID','DESC')->take(5)->get();
        return view('livewire.help-center',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'blogcomments'=>$blogcomments,'blogs'=>$blogs,'clientfeedbacks'=>$clientfeedbacks])->layout('layouts.base');
    }
}
