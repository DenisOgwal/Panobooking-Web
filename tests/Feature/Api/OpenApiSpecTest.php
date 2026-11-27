<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/** Keeps docs/openapi.yaml in step with routes/api.php. */
class OpenApiSpecTest extends TestCase
{
    public function test_every_api_route_is_documented(): void
    {
        $spec = file_get_contents(base_path('docs/openapi.yaml'));
        preg_match_all('/^  (\/[^\s:]*):\s*$/m', $spec, $m);
        $documented = $m[1];

        $missing = [];
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (! str_starts_with($uri, 'api/v1/')) {
                continue;
            }
            $path = substr($uri, strlen('api/v1'));
            foreach (array_diff($route->methods(), ['HEAD']) as $method) {
                $method = strtolower($method);
                $block = $this->pathBlock($spec, $path);
                if (! in_array($path, $documented, true) || ! preg_match('/^    ' . $method . ':/m', $block)) {
                    $missing[] = strtoupper($method) . ' ' . $path;
                }
            }
        }

        $this->assertSame([], $missing, 'Routes missing from docs/openapi.yaml');
    }

    private function pathBlock(string $spec, string $path): string
    {
        $start = strpos($spec, "\n  {$path}:\n");
        if ($start === false) {
            return '';
        }
        $next = preg_match('/\n  \/[^\n]*:\n/', $spec, $m, PREG_OFFSET_CAPTURE, $start + 1) ? $m[0][1] : strlen($spec);

        return substr($spec, $start, $next - $start);
    }
}
