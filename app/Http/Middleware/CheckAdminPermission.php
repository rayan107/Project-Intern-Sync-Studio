<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        // Super admin bypass
        if ($admin->hasRole('super_admin')) {
            return $next($request);
        }

        // permission check (Spatie ONLY)
        if (!$admin->can($permission)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}