<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->rol === 'admin') {
            return $next($request);
        }
        return response()->json(['message' => 'No autorizado'], 403);
    }
}
