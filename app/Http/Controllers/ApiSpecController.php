<?php

namespace App\Http\Controllers;

/** GET /api/openapi.yaml: the mobile API specification (docs/openapi.yaml). */
class ApiSpecController extends Controller
{
    public function __invoke()
    {
        return response()->file(base_path('docs/openapi.yaml'), [
            'Content-Type' => 'application/yaml; charset=utf-8',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
