<?php

namespace App\Http\Livewire;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use Illuminate\Http\Request;
use App\Models\TaxiModel;
use Livewire\Component;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class TaxiListComponent extends Component
{
    public $sorting;
    public $pagesize;
    public $taxislug;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
        $this->sorting="default";
        $this->pagesize=24;
        $this->newtaxislug=request()->taxicategoriesslug;
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
            return redirect()->route('TaxisList',['taxicategoriesslug'=> $this->newtaxislug]);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
        
    }
    public function render(Request $request)
    {
        $pickuplocation = $request->input('taxicategoriesslug');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
    if($this->sorting=="Name"){
        if( $request->input('taxicategoriesslug')){
        $carcategories=TaxiModel::where('CarCategory',$pickuplocation)->orderby('TaxiNo','ASC')->paginate($this->pagesize);
        }else{
            $carcategories=TaxiModel::orderby('TaxiNo','ASC')->paginate($this->pagesize);
        }
    }else if ($this->sorting=="Price"){
        if( $request->input('taxicategoriesslug')){
            $carcategories=TaxiModel::where('CarCategory',$pickuplocation)->orderby('Price','ASC')->paginate($this->pagesize);
            }else{
                $carcategories=TaxiModel::orderby('Price','ASC')->paginate($this->pagesize);
            }
    }
    else if ($this->sorting=="default"){
        if( $request->input('taxicategoriesslug')){
            $carcategories=TaxiModel::where('CarCategory',$pickuplocation)->paginate($this->pagesize);
            }else{
                $carcategories=TaxiModel::paginate($this->pagesize);
            }
    }else{
        if( $request->input('taxicategoriesslug')){
            $carcategories=TaxiModel::where('CarCategory',$pickuplocation)->paginate($this->pagesize);
            }else{
                $carcategories=TaxiModel::paginate($this->pagesize);
            }
    }
    $currencypick=$this->currencytype;
    $currenyvalues=$this->currenyvalue;
    $firstdestinations=TaxiModel::groupBy('CarCategory')->select('CarCategory', \DB::raw('COUNT(*) as cnt'))->get()->groupBy('CarCategory');
        return view('livewire.taxi-list-component',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carcategories'=>$carcategories,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'firstdestinations'=>$firstdestinations])->layout('layouts.basess');
    }
}
