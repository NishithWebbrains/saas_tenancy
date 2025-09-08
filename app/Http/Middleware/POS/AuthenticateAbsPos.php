<?php 
namespace App\Http\Middleware\POS;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAbsPos
{
    public function handle($request, Closure $next)
    {
        $tenant = $request->route('tenant')
            ?? $request->segment(1)
            ?? $request->input('tenant')
            ?? null;

        // Check tenant-specific guard first
        if (Auth::guard('abspos')->check()) {
            $user = Auth::guard('abspos')->user();
            if ($user->pos_type !== 'abspos') {
                Auth::guard('abspos')->logout();
                return redirect()->route('abspos.login', ['tenant' => $tenant])->with('error', 'Access denied.');
            }
            return $next($request);
        }

        //Check central DB guard for superadmin/storeadmin
        if (Auth::guard('web')->check()) {
            $centralUser = Auth::guard('web')->user();
            if ($centralUser->hasRole('superadmin'))
            {
                // Optionally: you could also add audit logging here
                return $next($request);
            }
            if ($centralUser->hasRole('storeadmin')) {
                // Only allowed to access their own tenant for ABSPOS
                $storeExists = \App\Models\Store::where('tenant_id', $tenant)
                    ->where('owner_user_id', $centralUser->id)
                    ->where('pos_type', 'abspos') // optional, for strictness
                    ->exists();
                if ($storeExists) {
                    return $next($request);
                } else {
                    return redirect()->route('abspos.login', ['tenant' => $tenant])
                        ->with('error', 'Access denied - not owner of this store.');
                }
            }
        }

        return redirect()->route('abspos.login', ['tenant' => $tenant]);
    }
}
