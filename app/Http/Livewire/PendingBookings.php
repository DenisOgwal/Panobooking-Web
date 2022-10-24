<?php

namespace App\Http\Livewire;

use App\Models\BlogModel;
use App\Models\HotelsModel;
use App\Models\CartModel;
use Livewire\Component;
use Illuminate\Http\Request;
class PendingBookings extends Component
{
    public $emails;

    public function render(Request $request)
    {
        $pickuplocation = $request->input('bookingslug');
        CartModel::where('IDs', $pickuplocation)->delete();
        $this->emails=session()->get('pano_client_data.Email');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::where('Approved','Approved')->orderBy('TrendRank','ASC')->take(5)->get();
        $bookings=CartModel::where('User',$this->emails)->where('Confirmed','Pending')->orderby('IDs','Desc')->get();
        return view('livewire.pending-bookings',['bookings'=>$bookings,'topproperties'=>$topproperties,'topblogs'=>$topblogs])->layout('layouts.basesssss');
    }
}
