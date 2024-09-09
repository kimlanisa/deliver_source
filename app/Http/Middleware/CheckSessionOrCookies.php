<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CheckSessionOrCookies
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah ada session atau cookies yang diperlukan
        if (!$request->session()->has('user_id')) {
            // Jika tidak ada, redirect ke halaman login
            return Redirect::route('login');
        }

        // Jika ada, lanjutkan ke request selanjutnya
        return $next($request);
    }
}