<?php

namespace App\Livewire;
use App\Support\CustomerAuth;
use Livewire\Attributes\Url;
use App\Models\HotelsModel;
use Livewire\Component;
use App\Models\BlogModel;
use App\Models\CarIncludes;
use App\Models\RentalCarModel;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
use Illuminate\Http\Request;
class CarRentalDetails extends Component
{
    #[Url(as: 'carslug')]
    public $url_carslug;
    public $email;
    public $name;
    public $phonenum;
    public $carslug;
    public $clientemail;
    public $password;
    public $newcarslug;
    public $currencytype;
    public $currenyvalue;
    public function mount(){
    $this->newcarslug=request()->carslug;
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
            return redirect()->route('CarDetails',['carslug'=> $this->newcarslug]);
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
            return redirect()->route('CarDetails',['carslug'=> $this->newcarslug]);
        }

        $message = $result === CustomerAuth::RESULT_BAD_PASSWORD
            ? 'That password is incorrect.'
            : 'No approved account exists for that email address.';
        session()->flash('alert', ['type' => 'error', 'message' => $message]);
        return redirect()->route('CarDetails',['carslug'=> $this->newcarslug]);
    }
    public function render()
    {
        $request = request();
        $carnumber = $this->url_carslug;
        $cars=RentalCarModel::where('IDs',$carnumber)->get();
        abort_if($cars->isEmpty(), 404);
        $collection=$cars;
        $plucked = $collection->first()->DriverName;
        $plucked1 = $collection->first()->TaxiNo;
        $plucked2 = $collection->first()->CarCategory;
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $carCat=RentalCarModel::approved()->where('CarCategory',$plucked2)->where('IDs','!=',$carnumber)->limit(10)->get();
        $carincludes=CarIncludes::where('Car',$plucked1)->where('Company',$plucked)->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.car-rental-details',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'carCat'=>$carCat,'carincludes'=>$carincludes,'cars'=>$cars,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basess');
    }
}
