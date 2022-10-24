<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\AmbulanceModel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\LoginModel;
use App\Models\CurrencySelect;

class AmbulanceListComponent extends Component
{
    public $sorting;
    public $pagesize;
    public $ambulancecategoriesslug;
    public $pickuplocations;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
        $this->sorting="default";
        $this->pagesize=24;
        $this->pickuplocations= request()->ambulancecategoriesslug;
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
            return redirect()->to('/AmbulanceList?ambulancecategoriesslug='.$this->pickuplocations);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
        
    }
    public function render(Request $request)
    {
        $pickuplocation = $request->input('ambulancecategoriesslug');
        
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
    if($this->sorting=="Name"){
        if( $request->input('ambulancecategoriesslug')){
        $carcategories=AmbulanceModel::where('AmbulanceCategory',$this->pickuplocations)->orderby('IDs','ASC')->paginate($this->pagesize);
        }else{
            $carcategories=AmbulanceModel::orderby('IDs','ASC')->paginate($this->pagesize);
        }
    }else if ($this->sorting=="Price"){
        if( $request->input('ambulancecategoriesslug')){
            $carcategories=AmbulanceModel::where('AmbulanceCategory',$this->pickuplocations)->orderby('Price','ASC')->paginate($this->pagesize);
            }else{
                $carcategories=AmbulanceModel::orderby('Price','ASC')->paginate($this->pagesize);
            }
    }
    else if ($this->sorting=="default"){
        if( $request->input('ambulancecategoriesslug')){
            $carcategories=AmbulanceModel::where('AmbulanceCategory',$pickuplocation)->paginate($this->pagesize);
            }else{
                $carcategories=AmbulanceModel::paginate($this->pagesize);
            }
    }else{
        if( $request->input('ambulancecategoriesslug')){
            $carcategories=AmbulanceModel::where('AmbulanceCategory',$pickuplocation)->paginate($this->pagesize);
            }else{
                $carcategories=AmbulanceModel::paginate($this->pagesize);
            }
    }
    $currencypick=$this->currencytype;
    $currenyvalues=$this->currenyvalue;
    $firstdestinations=AmbulanceModel::groupBy('AmbulanceCategory')->select('AmbulanceCategory', \DB::raw('COUNT(*) as cnt'))->get()->groupBy('AmbulanceCategory');
        return view('livewire.ambulance-list-component',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carcategories'=>$carcategories,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'firstdestinations'=>$firstdestinations])->layout('layouts.basess');
    }
}
