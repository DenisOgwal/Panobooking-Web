<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\HotelsModel;
class fetchdata extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function fetch_data(Request $request){
        if($request->ajax()){
            $from_city = $request->input('from_city');
            $hotelsearched=HotelsModel::where('City', 'LIKE',"%$from_city%")->paginate(3);
            return view('livewire.hotelpagination',compact('hotelsearched'))->render();
        }
    }
}