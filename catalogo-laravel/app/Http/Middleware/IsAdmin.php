<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class IsAdmin
{
     public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        if ($user->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        return $next($request);
    }
}