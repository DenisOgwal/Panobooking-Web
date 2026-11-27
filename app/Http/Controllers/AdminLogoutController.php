<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** POST /admin/logout. Leaves a customer session (CustomerAuth) in the same browser alone. */
class AdminLogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        Auth::logout();
        $request->session()->regenerate(true);
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
