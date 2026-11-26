<?php

namespace App\Livewire;
use Livewire\Attributes\Url;
use Illuminate\Http\Request;
use App\Models\HomeGuestModel;
use Livewire\Component;
use App\Models\HotelsModel;
use App\Models\BlogModel;

class HomeGuestSearch extends Component
{
    #[Url(as: 'Destination')]
    public $url_Destination;
    public function render()
    {
        $request = request();
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HotelsModel::orderBy('IDs','DESC')->take(5)->get();
        $Destination = $this->url_Destination;
        $homeguestsearched=HomeGuestModel::where('Place', 'like',"%$Destination%")->paginate(24);
        return view('livewire.home-guest-search',['topproperties'=>$topproperties,'topblogs'=>$topblogs])->with('homeguestsearched',$homeguestsearched)->layout('layouts.base');
    }
}
