<?php

namespace App\Http\Livewire;

use App\Models\BlogCommentCount;
use App\Models\BlogModel;
use App\Models\HotelsModel;
use App\Models\FeedBackModel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
class BlogDetailComponent extends Component
{
    //public BlogModel $blogss;
    public $blogslug;
    public $email;
    public $inputname;
    public $comments;
    public $blog_id;
    public $currencytype;
    public $currenyvalue;
    public function mount()
{
    $this->blog_id   = request()->blogslug;
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
            'email' => 'required|email',
            'inputname' => 'required|min:6',
            'comments' => 'required|min:20',
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
            return redirect()->to('/BlogDetail?blogslug='.$this->blog_id);
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
        
    }
    public function comment(Request $request)
    {
       $this->validate([
            'email' => 'required|email',
            'inputname' => 'required|min:6',
            'comments' => 'required|min:20',
        ]);
        $commentsmodel= new BlogCommentCount();
        $commentsmodel->blog_model_id = $this->blog_id;
        $commentsmodel->Comment = $this->comments;
        $commentsmodel->NameOfCommenter = $this->inputname;
        $commentsmodel->CommentEmail = $this->email;
        $commentsmodel->save();
        //$this->dispatchBrowserEvent('closeModal'); // Close model
    $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Comment Successful']);
    }
    public function render(Request $request)
    {
        $pickuplocation = $request->input('blogslug');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $blogs=BlogModel::where('ID',$pickuplocation)->get();
        $clientfeedbacks=FeedBackModel::where('Approved',"Approved")->get();
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $blogcomments=BlogCommentCount::where('blog_model_id',$pickuplocation)->where('Approval',"Approved")->get();
        return view('livewire.blog-detail-component',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'blogcomments'=>$blogcomments,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'blogs'=>$blogs,'clientfeedbacks'=>$clientfeedbacks])->layout('layouts.base');
    }
}
