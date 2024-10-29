<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        // Verifica el token desde el archivo .env
        if ($token !== config('app.api_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Verifica el token desde la base de datos (opcional)
        /*
        if (!DB::table('api_tokens')->where('token', $token)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        */

        return $next($request);
    }
}
