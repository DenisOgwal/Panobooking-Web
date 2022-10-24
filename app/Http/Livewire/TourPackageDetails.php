<?php

namespace App\Http\Livewire;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
use Illuminate\Http\Request;
use App\Models\BlogModel;
use App\Models\TourismSite;
use App\Models\HotelsModel;
use App\Models\CartModel;
use App\Models\TourPackages;
use Livewire\Component;

class TourPackageDetails extends Component
{
    public $newsearchslug;
    public $searchslug;
    public $clientname;
    public $clientemail;
    public $password;
    public $currencytype;
    public $currenyvalue;
    public $tourcompany;
    public $tourpckage;
    public $packageprice;
    public $packageids;
    public $packageprices;
    public $packageemail;
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
            return redirect()->to('/TourPackageDetails?searchslug='.$this->newsearchslug);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
        
    }
    public function mount(Request $request){
        $this->newsearchslug=request()->searchslug;
        $this->packageids= $request->input('searchslug');
        $this->tourcompany=TourPackages::where('IDs',$this->packageids)->value('TravelCompany');
        $this->tourpckage=TourPackages::where('IDs',$this->packageids)->value('PackageName');
        $this->packageprice=TourPackages::where('IDs',$this->packageids)->value('PackageFees2');
        $this->packageemail=TourPackages::where('IDs',$this->packageids)->value('EmailAddress');
        $this->email=session()->get('pano_client_data.Email');
        $this->fullnames=session()->get('pano_client_data.FullNames');

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
    public function isOnline($site='https://www.youtube.com/'){
        if(@fopen($site,"r")){
            return true;
        }else{
            return false;
        }
    }
    public function submitbooking(Request $request){
        if($request->session()->has('pano_client_data')){
            $this->packageprices=ceil((int)ceil($this->packageprice) / $this->currenyvalue);
            $cartmodel= new CartModel();
            $cartmodel->Facility = $this->tourcompany;
            $cartmodel->ProductName = $this->tourpckage;
            $cartmodel->User = $this->email;
            $cartmodel->UserNames = $this->fullnames;
            $cartmodel->Quantity = 1;
            $cartmodel->Cost = $this->packageprice;
            $cartmodel->OrderCode = 'N/A';
            $cartmodel->DatesFrom = 'N/A';
            $cartmodel->DatesTo = 'N/A';
            $cartmodel->BookingType = "Tour Package";
            $cartmodel->Currency = $this->currencytype;
            $cartmodel->CurrencyAmount  = ceil($this->packageprices);
            $cartmodel->Descriptions ='Please Reserve '.$this->tourpckage.'Package for '.$this->fullnames;
            $cartmodel->save();
            $this->mynotesbook='Please Reserve for '.$this->fullnames.' '.$this->tourpckage.' Package';
            $mynotesbooks = urlencode ( $this->mynotesbook );
            $sites='https://www.panobooking.com/SendEmails/SendPackage.php?packageemail='.$this->packageemail.'&bookingnotes='.$mynotesbooks;
            @fopen( $sites,"r");
            
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Reservation Successful']);//Booking Successful
            return redirect()->to('/BookingSuccess');
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Booking Faied!']);  
            }
    }
    public function signin(Request $request)
    {
       
       $this->validate([
            'clientemail' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $login=LoginModel::where('Email',$this->clientemail)->where('ApprovalStatus', 'Approved');
        $collection=$login;
        if($login->first()){
             $plucked = $collection->pluck('Password')->first();
            if($plucked==md5($this->password)){
            $request->session()->put('pano_client_data',$login->first());
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'User Login Successfully!']);
            return redirect()->route('TourPackageDetails',['searchslug'=> $this->newsearchslug]);
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' =>'Login Password Does not Match Registered Password']);  
                return redirect()->route('TourPackageDetails',['searchslug'=> $this->newsearchslug]);
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Account Does not Exist OR Account Not Approved!']);  
            return redirect()->route('TourPackageDetails',['searchslug'=> $this->newsearchslug]);
        }
    }
    public function render(Request $request)
    {
        $searchslugs = $request->input('searchslug');
        $tourismsites=TourPackages::where('IDs',$searchslugs)->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $mytime = \Carbon\Carbon::now();
        $currentda=$mytime->toDateString();
        $tourismsitess=TourPackages::where('TravelDate','>',$currentda)->orderBy('IDs','ASC')->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.tour-package-details',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'tourismsites'=>$tourismsites,'tourismsitess'=>$tourismsitess])->layout('layouts.basess');
    }
}
