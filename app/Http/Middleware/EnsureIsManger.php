<?php

namespace App\Http\Middleware;

use App\Exceptions\UNAUTHORIZED;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsManger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws UNAUTHORIZED
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->hasRole('manager')) {
            return $next($request);
        }

        throw new UNAUTHORIZED;
    }
}
