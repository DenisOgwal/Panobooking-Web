<?php

namespace App\Http\Livewire;
use App\Models\BlogModel;
use Illuminate\Http\Request;
use App\Models\DestinationsModel;
use App\Models\HomeGuestModel;
use Livewire\Component;

class GuestHouseList extends Component
{public $sorting;
    public $pagesize;
    public function mount(){
        $this->sorting="default";
        $this->pagesize=12;
    }
    public function render(Request $request)
    {
        $pickuplocation = $request->input('destinationslug');
        $topblogs=BlogModel::orderBy('ID','DESC')->take(5)->get();
        $topproperties=HomeGuestModel::orderBy('IDs','DESC')->take(5)->get();
    if($this->sorting=="Name"){
        if( $request->input('destinationslug')){
        $hotels=HomeGuestModel::where('City',$pickuplocation)->orderby('Place','ASC')->paginate($this->pagesize);
        }else{
            $hotels=HomeGuestModel::orderby('Place','ASC')->paginate($this->pagesize);
        }
    }else if ($this->sorting=="Price"){
        if( $request->input('destinationslug')){
            $hotels=HomeGuestModel::where('City',$pickuplocation)->orderby('Prices','ASC')->paginate($this->pagesize);
            }else{
                $hotels=HomeGuestModel::orderby('Prices','ASC')->paginate($this->pagesize);
            }
    }
    else if ($this->sorting=="default"){
        if( $request->input('destinationslug')){
            $hotels=HomeGuestModel::where('City',$pickuplocation)->paginate($this->pagesize);
            }else{
                $hotels=HomeGuestModel::paginate($this->pagesize);
            }
    }else{
        if( $request->input('destinationslug')){
            $hotels=HomeGuestModel::where('City',$pickuplocation)->paginate($this->pagesize);
            }else{
                $hotels=HomeGuestModel::paginate($this->pagesize);
            }
    }
        $firstdestinations=HomeGuestModel::groupBy('City')->select('City', \DB::raw('COUNT(*) as cnt'))->get()->groupBy('City');
        return view('livewire.guest-house-list',['hotels'=>$hotels,'topproperties'=>$topproperties,'topblogs'=>$topblogs,'firstdestinations'=>$firstdestinations])->layout('layouts.basess');
    }
}
