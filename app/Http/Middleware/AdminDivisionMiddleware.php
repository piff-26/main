<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDivisionMiddleware
{
    public function handle(Request $request, Closure $next, string ...$slugs): Response
    {
        $admin = Admin::with('division')->find(session('admin_id'));

        if (!$admin || !in_array($admin->division?->slug, $slugs)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
