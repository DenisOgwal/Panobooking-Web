<?php

namespace App\Http\Middleware;

use App\Support\Currency;
use Closure;
use Illuminate\Http\Request;

/**
 * Display currency for an API request: the `X-Currency` header or `?currency=`, else the
 * signed-in customer's saved currency, else UGX. Prices are always also returned in UGX.
 */
class ApiCurrency
{
    public function handle(Request $request, Closure $next)
    {
        $code = $request->header('X-Currency') ?: $request->query('currency');
        if (! $code && ($customer = auth('sanctum')->user())) {
            $code = $customer->SelectedCurrency;
        }

        app(Currency::class)->useForRequest($code ? (string) $code : Currency::BASE);

        $response = $next($request);
        $response->headers->set('X-Currency', app(Currency::class)->code());

        return $response;
    }
}
