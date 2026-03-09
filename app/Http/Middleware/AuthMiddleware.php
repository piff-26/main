<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('register_email')) {
            return redirect()->route('user.home')->with('error', 'Please login first!');
        }

        return $next($request);
    }
}
