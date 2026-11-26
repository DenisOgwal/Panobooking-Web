<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->session()->forget('pano_client_data');
        $request->session()->regenerateToken();

        return redirect('/')->with('alert', ['type' => 'success', 'message' => 'You have been logged out.']);
    }
}
