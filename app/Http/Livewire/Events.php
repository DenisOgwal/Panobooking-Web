<?php

namespace App\Http\Livewire;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
use App\Models\BlogModel;
use App\Models\events as ModelsEvents;
use App\Models\TourismSite;
use App\Models\HotelsModel;
use Illuminate\Http\Request;
use Livewire\Component;

class Events extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $clientname;
    public $clientemail;
    public $clientjob;
    public $clientcompany;
    public $clientfeedback;
    public $currencytype;
    public $currenyvalue;
    public $page;
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
            return redirect()->to('/Events');
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
        $parent_page_value=$request->page;

        $mytime = \Carbon\Carbon::now();
        $currentda=$mytime->toDateString();
        $eventss=ModelsEvents::where('EndDate','>=',$currentda)->orderBy('ID','ASC')->paginate(9);
       // $eventss=ModelsEvents::paginate(9);
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        return view('livewire.events',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'eventss'=>$eventss,'page' => $parent_page_value])->layout('layouts.base');
    }
}
