<?php
namespace App\Http\Livewire;
use App\Models\HotelsModel;
use App\Models\BlogModel;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\DestinationsModel;
use App\Models\LoginModel;
use App\Models\CurrencySelect;

class DetinationsListComponent extends Component
{
    public $sorting;
    public $pagesize;
    public $currencytype;
    public $currenyvalue;
    public $pickuplocations;
    public function mount(){
        $this->sorting="default";
        $this->pagesize=24;
        $this->pickuplocations= request()->destinationslug;
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
                return redirect()->to('/DestinationsList?destinationslug='.$this->pickuplocations);
            }else{
                $this->currencytype="UGX";
                $this->currenyvalue=1;
                $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
                return redirect()->to('/Login');
            }
            
        }
    public function render(Request $request)
    {
        $pickuplocation = $request->input('destinationslug');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
    if($this->sorting=="Name"){
        if( $request->input('destinationslug')){
        $hotels=HotelsModel::where('City',$pickuplocation)->where('Approved','Approved')->orderby('Place','ASC')->paginate($this->pagesize);
        }else{
            $hotels=HotelsModel::where('Approved','Approved')->orderby('Place','ASC')->paginate($this->pagesize);
        }
    }else if ($this->sorting=="Price"){
        if( $request->input('destinationslug')){
            $hotels=HotelsModel::where('City',$pickuplocation)->where('Approved','Approved')->orderby('Prices','ASC')->paginate($this->pagesize);
            }else{
                $hotels=HotelsModel::where('Approved','Approved')->orderby('Prices','ASC')->paginate($this->pagesize);
            }
    }
    else if ($this->sorting=="default"){
        if( $request->input('destinationslug')){
            $hotels=HotelsModel::where('City',$pickuplocation)->where('Approved','Approved')->paginate($this->pagesize);
            }else{
                $hotels=HotelsModel::where('Approved','Approved')->paginate($this->pagesize);
            }
    }else{
        if( $request->input('destinationslug')){
            $hotels=HotelsModel::where('City',$pickuplocation)->where('Approved','Approved')->paginate($this->pagesize);
            }else{
                $hotels=HotelsModel::where('Approved','Approved')->paginate($this->pagesize);
            }
    }
    $currencypick=$this->currencytype;
    $currenyvalues=$this->currenyvalue;
        $firstdestinations=HotelsModel::groupBy('City')->select('City', \DB::raw('COUNT(*) as cnt'))->get()->groupBy('City');
        return view('livewire.detinations-list-component',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'hotels'=>$hotels,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'firstdestinations'=>$firstdestinations])->layout('layouts.basess');
    }
}
