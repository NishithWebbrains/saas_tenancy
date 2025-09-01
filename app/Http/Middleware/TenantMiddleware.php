<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store; // Your tenant model

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $tenantPath = $request->route('tenantPath'); // Capture tenant from URL

        // Lookup tenant (store) by path or slug
        $tenant = Store::where('slug', $tenantPath)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Here you can setup tenant context, e.g.:
        // - set tenant-specific db connection
        // - share tenant info globally
        app()->instance('currentTenant', $tenant);

        // Optionally switch database or environment
        // config(['database.connections.tenant.database' => $tenant->db_name]);
        // DB::setDefaultConnection('tenant');

        return $next($request);
    }
}
