<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\BlogModel;
use App\Models\Contact;
use App\Models\HotelsModel;
use Livewire\Component;
use App\Models\LoginModel;
use App\Models\CurrencySelect;
class ContactPage extends Component
{ 
    public $inputname;
    public $email;
    public $contactinfo;
    public $currencytype;
    public $currenyvalue;
    
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
            'inputname'=>'required|min:6',
            'email'=>'required|email',
            'contactinfo'=>'required|min:20',
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
            return redirect()->to('/contactus'); 
        }else{
            $this->currencytype="UGX";
            $this->currenyvalue=1;
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Please Login to Change Currency!']);
            return redirect()->to('/Login');
        }
        
    }
public function sendmessage(){
    $data =$this->validate([
        'inputname'=>'required|min:6',
        'email'=>'required|email',
        'contactinfo'=>'required|min:20',
    ]);
    //$this->inputname
    if($this-> isOnline()){
        $contactmodel= new Contact();
        $contactmodel->Names = $this->inputname;
        $contactmodel->Email = $this->email;
        $contactmodel->Messages = $this->contactinfo;
        $contactmodel->save();
        /*$mail_data=[
            'recipient'=>'pano.booking2021@gmail.com',
            'fromEmail'=>$this->email,
            'fromName'=>$this->inputname,
            'Subject'=>'Inquiry',
            'body'=>$this->contactinfo,
        ];
        Mail::send('layouts.email-template',$mail_data, function($message) use($mail_data){
            $message->to('pano.booking2021@gmail.com')
            ->from('sales.panobooking@gmail.com' ,'panobooking.com',)
            ->subject('Inquiry');
        });*/
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Message Sent Successfully!']);
        $this->inputname="";
        $this->contactinfo="";
        $this->email="";
        return back()->with('success', 'Thanks for contacting us, I will get back to you soon!');
        //session()->flash('success','email sent');
        //return redirect()->back()->with('success','email sent');
    }else{
        session()->flash('success','email sent');
        return redirect()->back()->withInput()->with('error','Check your internet connection');
    }
    //dd($this->inputname,$this->emails);
  
}
public function isOnline($site='https://www.youtube.com/'){
    if(@fopen($site,"r")){
        return true;
    }else{
        return false;
    }
}

    public function render()
    {
        $currencypick=$this->currencytype;
        $currenyvalues=$this->currenyvalue;
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        return view('livewire.contact-page',['currenyvalue'=>$currenyvalues,'currencypick'=>$currencypick,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.base');
    }
}
