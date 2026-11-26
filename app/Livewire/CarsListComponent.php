<?php

namespace App\Livewire;
use Livewire\Attributes\Url;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use App\Models\RentalCarModel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
class CarsListComponent extends Component
{
    #[Url(as: 'carcategoriesslug')]
    public $url_carcategoriesslug;
    public $sorting;
    public $pagesize;
    public $currencytype;
    public $currenyvalue;
    public $pickuplocations;
    public function mount(){
        $this->sorting="default";
        $this->pagesize=24;
        $this->pickuplocations= request()->carcategoriesslug;
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
                return redirect()->to('/CarsList?carcategoriesslug='.$this->pickuplocations);
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
        $pickuplocation = $this->url_carcategoriesslug;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
    if($this->sorting=="Name"){
        if( $this->url_carcategoriesslug){
        $carcategories=RentalCarModel::approved()->where('CarCategory',$pickuplocation)->orderby('TaxiNo','ASC')->paginate($this->pagesize);
        }else{
            $carcategories=RentalCarModel::approved()->orderby('TaxiNo','ASC')->paginate($this->pagesize);
        }
    }else if ($this->sorting=="Price"){
        if( $this->url_carcategoriesslug){
            $carcategories=RentalCarModel::approved()->where('CarCategory',$pickuplocation)->orderby('Price','ASC')->paginate($this->pagesize);
            }else{
                $carcategories=RentalCarModel::approved()->orderby('Price','ASC')->paginate($this->pagesize);
            }
    }
    else if ($this->sorting=="default"){
        if( $this->url_carcategoriesslug){
            $carcategories=RentalCarModel::approved()->where('CarCategory',$pickuplocation)->paginate($this->pagesize);
            }else{
                $carcategories=RentalCarModel::approved()->paginate($this->pagesize);
            }
    }else{
        if( $this->url_carcategoriesslug){
            $carcategories=RentalCarModel::approved()->where('CarCategory',$pickuplocation)->paginate($this->pagesize);
            }else{
                $carcategories=RentalCarModel::approved()->paginate($this->pagesize);
            }
    }
    $currencypick=$this->currencytype;
    $currenyvalues=$this->currenyvalue;
    $firstdestinations=RentalCarModel::approved()->groupBy('CarCategory')->select('CarCategory', \DB::raw('COUNT(*) as cnt'))->get()->groupBy('CarCategory');
        return view('livewire.cars-list-component',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carcategories'=>$carcategories,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'firstdestinations'=>$firstdestinations])->layout('layouts.basess');
    }
}
