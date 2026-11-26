<?php

namespace App\Livewire;
use App\Support\CustomerAuth;

use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\BlogModel;
use App\Models\HotelsModel;
use App\Models\CarIncludes;
use App\Models\TaxiModel;
use App\Models\TaxiTripModel;
use Illuminate\Http\Request;
use App\Models\CurrencySelect;
use App\Models\LoginModel;
class TaxiDetails extends Component
{
    #[Url(as: 'taxislug')]
    public $url_taxislug;
    public $datas;
    public $email;
    public $name;
    public $phonenum;
    public $taxislug;
    public $clientemail;
    public $password;
    public $newtaxislug;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
    $this->newtaxislug=request()->taxislug;
    $this->datas=TaxiTripModel::all()->toArray();
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
        $this->validateOnly($fileds,[
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
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
            return redirect()->route('TaxiDetails',['taxislug'=> $this->newtaxislug]);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatch('alert', type: 'success',  message: 'Please Login to Change Currency!');
            return redirect()->to('/Login');
        }    
    }
    public function signin() {
       
       $this->validate([
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $result = app(CustomerAuth::class)->attempt($this->clientemail, $this->password);

        if ($result === CustomerAuth::RESULT_OK) {
            session()->flash('alert', ['type' => 'success', 'message' => 'Welcome back, ' . app(CustomerAuth::class)->name() . '!']);
            return redirect()->route('TaxiDetails',['taxislug'=> $this->newtaxislug]);
        }

        $message = $result === CustomerAuth::RESULT_BAD_PASSWORD
            ? 'That password is incorrect.'
            : 'No approved account exists for that email address.';
        session()->flash('alert', ['type' => 'error', 'message' => $message]);
        return redirect()->route('TaxiDetails',['taxislug'=> $this->newtaxislug]);
    }
    public function render()
    {
        $request = request();
        $carnumber = $this->url_taxislug;
        $cars=TaxiModel::where('IDs',$carnumber)->get();
        abort_if($cars->isEmpty(), 404);
        $collection=$cars;
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $plucked = $collection->first()->DriverName;
        $plucked1 = $collection->first()->TaxiNo;
        $plucked2 = $collection->first()->CarCategory;
        $carCat=TaxiModel::where('CarCategory',$plucked2)->where('IDs','!=',$carnumber)->limit(10)->get();
        $carincludes=CarIncludes::where('Car',$plucked1)->where('Company',$plucked)->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.taxi-details',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carCat'=>$carCat,'carincludes'=>$carincludes,'cars'=>$cars,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
