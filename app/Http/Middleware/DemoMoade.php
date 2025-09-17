<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DemoMode
{
    public function handle(Request $request, Closure $next)
    {
        if (config('app.demo', false)) {
            // Nur GET/HEAD/OPTIONS erlauben – alles andere blocken
            if (!in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
                return response()->json(['message' => 'Demo is read-only.'], 423);
            }
        }
        return $next($request);
    }
}
