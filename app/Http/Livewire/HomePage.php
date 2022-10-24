<?php

namespace App\Http\Livewire;
use App\Models\UserRegistrationModel;
use App\Models\BlogModel;
use App\Models\DestinationsModel;
use App\Models\FeedBackModel;
use App\Models\HotelsModel;
use App\Models\RoomsModel;
use Livewire\Component;
use App\Models\TourismSite;
use App\Models\events as ModelsEvents;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\TaxiTripModel;
use App\Models\BlogCommentCount;
use App\Models\CurrencySelect;
use App\Models\LoginModel;

//use GuzzleHttp\Psr7\Request;

class HomePage extends Component
{
//use WithPagination;
public $clientname;
public $clientemail;
public $clientjob;
public $clientcompany;
public $clientfeedback;
public $currencytype;
public $currenyvalue;
public function updated($fields){
    $this->validateOnly($fields,[
        'clientname'=>'required|min:6',
        'clientemail'=>'required|email',
        'clientcompany'=>'required',
        'clientfeedback'=>'required|min:20',
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
        return redirect()->to('/');
    }else{
        $this->currencytype="UGX";
        $this->currenyvalue=1;
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
        return redirect()->to('/Login');
    }
    
}
public function mount(){
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

public function submitfeedback(){
$data =$this->validate([
    'clientname'=>'required|min:6',
    'clientemail'=>'required|email',
    'clientcompany'=>'required',
    'clientfeedback'=>'required|min:20',
]);
$feedbackmodel= new FeedBackModel();
$feedbackmodel->ClientName = $this->clientname;
$feedbackmodel->ClientEmail = $this->clientemail;
$feedbackmodel->ClientJob = $this->clientjob;
$feedbackmodel->ClientCompany = $this->clientcompany;
$feedbackmodel->FeedBack = $this->clientfeedback;
$feedbackmodel->save();
$this->emit('userStore');
$this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Feedback Received!']);
}
    public function render()
    {
        $hotels=HotelsModel::where('Trending','Yes')->where('Approved','Approved')->orderBy('TrendRank','ASC')->limit(15)->get();
        $hotelsfeatured=HotelsModel::where('Featured','Yes')->where('Approved','Approved')->orderBy('FaturedRank','ASC')->limit(15)->get();
        $blogs=BlogModel::withCount('comments')->get();
        $blogcomments=BlogCommentCount::all();
        $clientfeedbacks=FeedBackModel::where('Approved',"Approved")->get();
        $destinations=DestinationsModel::all();
        $tourismsites=TourismSite::orderBy('ID','ASC')->limit(10)->get();
        $mytime = \Carbon\Carbon::now();
        $currentda=$mytime->toDateString();
        $eventss=ModelsEvents::where('EndDate','>=',$currentda)->orderBy('ID','ASC')->limit(10)->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        $hoteloffered=RoomsModel::where('Availability','Available')->orderBy('Cost','ASC')->limit(1)->get();
        $offeredroom=RoomsModel::where('Offered','Offered')->orderBy('IDs','DESC')->limit(1)->get();
        $seconddestinations=DestinationsModel::orderBy('DestinationID','ASC')->skip(6)->take(5)->get();
        $firstdestinations=DestinationsModel::orderBy('DestinationID','ASC')->take(5)->get();
        return view('livewire.home-page',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'hotelsfeatured'=>$hotelsfeatured,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'blogcomments'=>$blogcomments,'hoteloffered'=>$hoteloffered,'offeredroom'=>$offeredroom,'hotels'=>$hotels,'destinations'=> $destinations,'firstdestinations'=>$firstdestinations,'seconddestinations'=>$seconddestinations,'blogs'=>$blogs,'clientfeedbacks'=>$clientfeedbacks,'tourismsites'=>$tourismsites,'eventss'=>$eventss,'currentda'=>$currentda])->layout('layouts.base');
    }
    
}
