<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!auth('admin')->check()) {
            
            $allowedRoutes = [
                'admin/login',
                'admin/forgot-password',
                'admin/reset-password',
            ];
            
            foreach ($allowedRoutes as $route) {
                if ($request->is($route) || $request->is($route . '/*')) {
                    return $next($request);
                }
            }
            
            return redirect()->route('admin.login')->with('error', 'Please login first to access this page.');
        }

        return $next($request);
    }

    protected function redirectTo(Request $request): ?string
    {
        return route('admin.login');
    }
}