<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('role') || session('role') !== 'admin') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }
            return redirect()->route('admin.login')->with('error', 'You are not authorized.');
        }

        return $next($request);
    }
}
