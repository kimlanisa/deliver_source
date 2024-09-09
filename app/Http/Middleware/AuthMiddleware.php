<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
        	return redirect('/login');
        }

        return $next($request);
    }
}