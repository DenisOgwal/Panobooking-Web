<?php
namespace App\Http\Livewire;
use App\Models\BlogModel;
use App\Models\FeedBackModel;
use Livewire\Component;
use App\Models\HotelsModel;
use Illuminate\Support\Facades\Cache;
use App\Http\Livewire\BlogDetailComponent;
use App\Models\BlogCommentCount;
use Livewire\WithPagination;
use App\Models\LoginModel;
use Illuminate\Http\Request;
use App\Models\CurrencySelect;
class BlogPage extends Component
{
    //use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $clientname;
    public $clientemail;
    public $clientjob;
    public $clientcompany;
    public $clientfeedback;
    public $currencytype;
    public $currenyvalue;
    public $page;
    public function mount(){

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
            return redirect()->to('/blog');
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
   // use WithPagination;
    //protected $paginationTheme = 'bootstrap';
    public function render(Request $request)
    {
        $parent_page_value=$request->page;
        Cache::forget('key');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $blogcomments=BlogCommentCount::all();
        $blogs= BlogModel::withCount('comments')->paginate(6);
        $clientfeedbacks=FeedBackModel::where('Approved',"Approved")->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        return view('livewire.blog-page',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'blogcomments'=>$blogcomments,'blogs'=>$blogs,'clientfeedbacks'=>$clientfeedbacks,'page' => $parent_page_value])->layout('layouts.base');
    }
}
