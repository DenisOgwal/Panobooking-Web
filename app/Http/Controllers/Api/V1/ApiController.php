<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserRegistrationModel;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
    protected const PER_PAGE = 12;
    protected const MAX_PER_PAGE = 50;

    protected function perPage(Request $request): int
    {
        return max(1, min(self::MAX_PER_PAGE, (int) $request->query('per_page', self::PER_PAGE)));
    }

    protected function term(Request $request, string $key = 'q'): string
    {
        return trim((string) $request->query($key, ''));
    }

    protected function customer(Request $request): UserRegistrationModel
    {
        return $request->user('sanctum');
    }
}
