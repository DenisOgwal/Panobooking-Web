<?php

namespace App\Http\Livewire;
use App\Models\BlogModel;
use App\Models\HotelsModel;
use App\Models\CartModel;
use Livewire\Component;

class Bookings extends Component
{
    public function render()
    {
        $this->emails=session()->get('pano_client_data.Email');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        $bookings=CartModel::where('User',$this->emails)->where('Confirmed','Confirmed')->orderby('IDs','Desc')->get();
        return view('livewire.bookings',['bookings'=>$bookings,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basesssss');
    }
}
