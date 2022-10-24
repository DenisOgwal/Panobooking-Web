<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\BlogModel;
use App\Models\HomeGuestDestinationsModel;
use App\Models\FeedBackModel;
use App\Models\HomeGuestModel;
use App\Models\HotelsModel;
use App\Models\BlogCommentCount;
use App\Models\TaxiTripModel;
class HomeGuestHouses extends Component
{
    //use WithPagination;
    public $clientname;
    public $clientemail;
    public $clientjob;
    public $clientcompany;
    public $clientfeedback;
public function mount(){
    $this->datas=TaxiTripModel::all()->toArray();
}
public function updated($fields){
        $this->validateOnly($fields,[
            'clientname'=>'required|min:6',
            'clientemail'=>'required|email',
            'clientcompany'=>'required',
            'clientfeedback'=>'required|min:20',
        ]);
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
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $guesthousess=HomeGuestModel::all();
        $blogs=BlogModel::withCount('comments')->get();
        $blogcomments=BlogCommentCount::all();
        $clientfeedbacks=FeedBackModel::all();
        $destinations=HomeGuestDestinationsModel::all();
        $seconddestinations=HomeGuestDestinationsModel::orderBy('IDs','DESC')->skip(5)->take(5)->get();
        $firstdestinations=HomeGuestDestinationsModel::orderBy('IDs','DESC')->take(5)->get();
        $hoteloffered=HomeGuestModel::where('Availability','Available')->orderBy('Prices','ASC')->limit(1)->get();
        $offeredroom=HomeGuestModel::where('Offered','Offered')->orderBy('IDs','DESC')->limit(1)->get();
        return view('livewire.home-guest-houses',['topproperties'=>$topproperties,'topblogs'=>$topblogs,'blogcomments'=>$blogcomments,'hoteloffered'=>$hoteloffered,'offeredroom'=>$offeredroom,'guesthousess'=>$guesthousess,'destinations'=> $destinations,'firstdestinations'=>$firstdestinations,'seconddestinations'=>$seconddestinations,'blogs'=>$blogs,'clientfeedbacks'=>$clientfeedbacks])->layout('layouts.base');
    }
}
