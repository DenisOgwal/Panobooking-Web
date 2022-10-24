<?php

namespace App\Http\Livewire;
use App\Models\LoginModel;
use Illuminate\Http\Request;
use Livewire\Component;

class LoginPage extends Component
{
    public $email;
    public $password;
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    }
    public function signin(Request $request)
    {
       
       $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $login=LoginModel::where('Email',$this->email)->where('ApprovalStatus', 'Approved');
        $collection=$login;
        if($login->first()){
             $plucked = $collection->pluck('Password')->first();
            if($plucked==md5($this->password)){
            $request->session()->put('pano_client_data',$login->first());
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'User Login Successfully!']);
            return redirect()->to('/');
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' =>'Login Password Does not Match Registered Password']);  
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Account Does not Exist OR Account Not Approved!']);  
    }
        //dd($this->name,$this->email,$this->password,$this->phonenum,'Progress');
    }
    public function logouts(Request $request){
        if($request->session()->has('pano_client_data')){
        $request->session()->forget('pano_client_data');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'User Logout Successful!']);
        return redirect()->to('/');
        }
    }
    public function render(Request $request)
    {
        if($request->session()->has('pano_client_data')){
            redirect()->to('/');
        }
        return view('livewire.login-page')->layout('layouts.bases');
    }
}
