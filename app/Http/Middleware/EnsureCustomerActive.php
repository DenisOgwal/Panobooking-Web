<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** API: a valid token whose customer was since suspended by an admin no longer works. */
class EnsureCustomerActive
{
    public function handle(Request $request, Closure $next)
    {
        $customer = $request->user('sanctum');

        if (! $customer || ! method_exists($customer, 'isApproved') || ! $customer->isApproved()) {
            $customer?->currentAccessToken()?->delete();

            return response()->json(['message' => 'This account is not active.', 'code' => 'inactive'], 403);
        }

        return $next($request);
    }
}
