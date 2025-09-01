<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckTenantAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {

        $user = $request->user();

        if ($user->hasRole('storeadmin')|| $user->hasRole('superadmin')) {
            return $next($request);
        }
        

        // Example: resolve tenant connection name dynamically
        $tenant = tenant(); // Stancl helper to get the current tenant model

        $hasAccess = DB::table('tenant_users')
            ->where('user_id', auth()->id())
            ->exists();
        if (! $hasAccess) {
            abort(403, 'Unauthorized to access this tenant');
        }

        return $next($request);
    }
}
