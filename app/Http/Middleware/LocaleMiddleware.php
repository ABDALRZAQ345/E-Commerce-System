<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        if(Auth::user()){
            $user=Auth::user();
            $user->last_login=now();
            $user->save();
        }

        return $next($request);
    }
}
