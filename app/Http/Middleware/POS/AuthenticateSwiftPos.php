<?php 
namespace App\Http\Middleware\POS;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateSwiftPos
{
        // public function handle($request, Closure $next)
        // {
        //     $tenant = $request->route('tenant')
        //     ?? $request->segment(1)
        //     ?? $request->input('tenant')
        //     ?? null; // this WILL exist because your route prefix is {tenant}
        //     //dd($tenant);
        //     if (!Auth::guard('swiftpos')->check()) {
        //         // Always pass tenant parameter for all route() calls
        //         return redirect()->route('swiftpos.login', ['tenant' => $tenant]);
        //     }

        //     $user = Auth::guard('swiftpos')->user();
        //     if ($user->pos_type !== 'swiftpos') {
        //         Auth::guard('swiftpos')->logout();
        //         return redirect()->route('swiftpos.login', ['tenant' => $tenant])->with('error', 'Access denied.');
        //     }

        //     return $next($request);
        // }
    public function handle($request, Closure $next)
    {
        $tenant = $request->route('tenant')
            ?? $request->segment(1)
            ?? $request->input('tenant')
            ?? null;

        // Check tenant-specific guard first
        if (Auth::guard('swiftpos')->check()) {
            $user = Auth::guard('swiftpos')->user();
            if ($user->pos_type !== 'swiftpos') {
                Auth::guard('swiftpos')->logout();
                return redirect()->route('swiftpos.login', ['tenant' => $tenant])->with('error', 'Access denied.');
            }
            return $next($request);
        }

        //Check central DB guard for superadmin/storeadmin
        if (Auth::guard('web')->check()) {
            $centralUser = Auth::guard('web')->user();
            if (
                $centralUser->hasRole('superadmin')
                || $centralUser->hasRole('storeadmin')
            ) {
                // Optionally: you could also add audit logging here
                return $next($request);
            }
        }

        return redirect()->route('swiftpos.login', ['tenant' => $tenant]);
    }


}
