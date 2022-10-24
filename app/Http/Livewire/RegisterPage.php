<?php

namespace App\Http\Livewire;
use App\Models\UserRegistrationModel;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class RegisterPage extends Component
{
    public $name;
    public $email;
    public $code;
    public $phonenum;
    public $password;
    public $confirmpassword;
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'name' => 'required|min:6',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'code' => 'required',
            'phonenum'=>'required',
            'confirmpassword'=>'required|min:8',
        ]);
    }
    public function submit()
    {
       
       $data = $this->validate([
            'name' => 'required|min:6',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'code' => 'required',
            'phonenum'=>'required',
            'confirmpassword'=>'required|min:8',
        ]);
        if($this->password==$this->confirmpassword){
        $UserRegistrationModel= new UserRegistrationModel();
        $UserRegistrationModel->FullNames = $this->name;
        $UserRegistrationModel->Email = $this->email;
        $UserRegistrationModel->Password = md5($this->password);
        $UserRegistrationModel->Telephone = $this->code.$this->phonenum;
        $UserRegistrationModel->ApprovalStatus = 'Not Approved';
        $UserRegistrationModel->save();
        $sites='https://www.panobooking.com/SendEmails/checkreg.php?emails='.$this->email;
        @fopen($sites,"r");
        $this->name='';
        $this->email='';
        $this->code='';
        $this->phonenum='';
        $this->password='';
        $this->confirmpassword='';
        /*if($this-> isOnline()){
            $mail_data=[
                'recipient'=>$this->email,
                'fromEmail'=>'sales.panobooking@gmail.com',
                'fromName'=>'PANOBOOKING ACCOUNTS',
                'Subject'=>'Account Openning',
                'body'=>'Please Click the button below to confirm your Panobooking account ',
            ];
            Mail::send('layouts.account_confirmation_Email',$mail_data, function($message) use($mail_data){
                $message->to($this->email)
                ->from('sales.panobooking@gmail.com' ,'PANOBOOKING ACCOUNTS')
                ->subject('Account Openning');
            });
        }else{
            session()->flash('success','email sent');
            return redirect()->back()->withInput()->with('error','Check your internet connection');
        }*/

        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'User Created Successfully!. A confirmation Email has been sent. Confirm Account Email to begin using your Account.']);
        //return redirect()->to('/Login');
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Password Does not match confirm Password!']);  
        }
        //dd($this->name,$this->email,$this->password,$this->phonenum,'Progress');
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
        return view('livewire.register-page')->layout('layouts.bases');
    
    }
    public function alertSuccess()
    {
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'User Created Successfully!']);
    }
    public function alertError()
    {
        $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Something is Wrong!']);
    }
    public function alertInfo()
    {
        $this->dispatchBrowserEvent('alert', ['type' => 'info',  'message' => 'Going Well!']);
    }
}
