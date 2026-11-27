<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Admin backend gate: a signed-in `users` account with an admin or staff role.
 * Customer sessions (CustomerAuth, `registration` table) never pass.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return $request->expectsJson()
                ? abort(401)
                : redirect()->guest(route('admin.login'));
        }

        abort_unless($user->canAccessAdmin(), 403);

        return $next($request);
    }
}
