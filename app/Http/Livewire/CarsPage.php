<?php

namespace App\Http\Livewire;
use App\Models\BlogModel;
use App\Models\FeedBackModel;
use App\Models\DestinationsModel;
use App\Models\RentalCarModel;
use Livewire\Component;
use App\Models\HotelsModel;
use App\Models\RoomsModel;
use App\Models\TaxiTripModel;
use App\Models\BlogCommentCount;
use App\Models\LoginModel;
use App\Models\CurrencySelect;

class CarsPage extends Component
{
    public $clientname;
    public $clientemail;
    public $clientjob;
    public $clientcompany;
    public $clientfeedback;
    public $currencytype;
    public $currenyvalue;
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
            return redirect()->to('/cars');
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
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
        $cars=RentalCarModel::where('Approved','Approved')->get();
        $blogs=BlogModel::withCount('comments')->get();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $blogcomments=BlogCommentCount::all();
        $clientfeedbacks=FeedBackModel::where('Approved',"Approved")->get();
        $destinations=DestinationsModel::all();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $hoteloffered=RentalCarModel::where('Availability','Available')->orderBy('IDs','ASC')->limit(1)->get();
        $offeredroom=RentalCarModel::where('Offered','Offered')->orderBy('IDs','DESC')->limit(1)->get();
        $seconddestinations=RentalCarModel::distinct()->get('CarCategory')->skip(5)->take(5);
        $firstdestinations=RentalCarModel::distinct()->get('CarCategory')->take(5);
        return view('livewire.cars-page',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'blogcomments'=>$blogcomments,'hoteloffered'=>$hoteloffered,'offeredroom'=>$offeredroom,'cars'=> $cars,'destinations'=> $destinations,'firstdestinations'=>$firstdestinations,'seconddestinations'=>$seconddestinations,'blogs'=>$blogs,'clientfeedbacks'=>$clientfeedbacks])->layout('layouts.base');
    }
}
