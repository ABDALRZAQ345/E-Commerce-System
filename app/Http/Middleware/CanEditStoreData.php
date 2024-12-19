<?php

namespace App\Http\Middleware;

use App\Exceptions\UNAUTHORIZED;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanEditStoreData
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
        $user = Auth::user();
        if (! $user->hasRole('manager')) {
            throw new UNAUTHORIZED;
        }
        if ( $user->store->id != $request->route('store')->id) {
            throw new UNAUTHORIZED;
        }

        return $next($request);
    }
}
