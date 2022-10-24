<?php

namespace App\Http\Livewire;
use Livewire\Component;

class Logout extends Component
{
    public function logout(){
        session()->forget('pano_client_data');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'User Logout Successful!']);
        return redirect()->to('/');
    }
}
