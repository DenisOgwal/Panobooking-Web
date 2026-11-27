<?php

namespace App\Http\Controllers;

use App\Support\CustomerAccounts;

/** GET /VerifyEmail/{id}/{hash} (signed): the one-click link in the sign-up email. */
class VerifyEmailLinkController extends Controller
{
    public function __invoke(int $id, string $hash)
    {
        $ok = app(CustomerAccounts::class)->verifyFromLink($id, $hash);

        return redirect('/Login')->with('alert', $ok
            ? ['type' => 'success', 'message' => 'Email verified. You can sign in now.']
            : ['type' => 'error', 'message' => 'That verification link is not valid.']);
    }
}
