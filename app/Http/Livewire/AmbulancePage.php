<?php

namespace App\Http\Livewire;

use App\Models\AmbulanceModel;
use App\Models\BlogModel;
use App\Models\FeedBackModel;
use App\Models\DestinationsModel;
use Livewire\Component;
use App\Models\RoomsModel;
use App\Models\HotelsModel;
use App\Models\BlogCommentCount;
use App\Models\TaxiTripModel;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
class AmbulancePage extends Component
{
    public $clientname;
    public $clientemail;
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
            return redirect()->to('/ambulance');
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
    $feedbackmodel->ClientCompany = $this->clientcompany;
    $feedbackmodel->FeedBack = $this->clientfeedback;
    $feedbackmodel->save();
    $this->emit('userStore');
    $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Feedback Received!']);
}
    public function render()
    {
        $Ambulances=AmbulanceModel::all();
        $blogs=BlogModel::withCount('comments')->get();
        $blogcomments=BlogCommentCount::all();
        $clientfeedbacks=FeedBackModel::where('Approved',"Approved")->get();
        $destinations=DestinationsModel::all();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $hoteloffered=AmbulanceModel::where('Availability','Available')->orderBy('IDs','ASC')->limit(1)->get();
        $offeredroom=AmbulanceModel::where('Offered','Offered')->orderBy('IDs','DESC')->limit(1)->get();
        $seconddestinations=AmbulanceModel::select('AmbulanceCategory')->distinct()->skip(2)->take(5)->get();
        $firstdestinations=AmbulanceModel::select('AmbulanceCategory')->distinct()->take(2)->get();
        return view('livewire.ambulance-page',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'blogcomments'=>$blogcomments,'hoteloffered'=>$hoteloffered,'offeredroom'=>$offeredroom,'Ambulances'=> $Ambulances,'destinations'=> $destinations,'firstdestinations'=>$firstdestinations,'seconddestinations'=>$seconddestinations,'blogs'=>$blogs,'clientfeedbacks'=>$clientfeedbacks])->layout('layouts.base');
    }
}
