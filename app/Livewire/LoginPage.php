<?php

namespace App\Livewire;
use App\Support\CustomerAuth;
use App\Models\LoginModel;
use Illuminate\Http\Request;
use Livewire\Component;

class LoginPage extends Component
{
    public $name;
    public $phonenum;
    public $email;
    public $password;
    public function updated($fileds){
        $this->validateOnly($fileds,[
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    }
    public function signin() {
       
       $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $result = app(CustomerAuth::class)->attempt($this->email, $this->password);

        if ($result === CustomerAuth::RESULT_OK) {
            session()->flash('alert', ['type' => 'success', 'message' => 'Welcome back, ' . app(CustomerAuth::class)->name() . '!']);
            return redirect()->to('/');
        }

        $message = $result === CustomerAuth::RESULT_BAD_PASSWORD
            ? 'That password is incorrect.'
            : 'No approved account exists for that email address.';
        $this->dispatch('alert', type: 'error', message: $message);
    }

    public function mount()
    {
        if (app(CustomerAuth::class)->check()) {
            return redirect()->to('/');
        }
    }

    public function render()
    {
        return view('livewire.login-page')->layout('layouts.bases');
    }
}
