<?php 
namespace POS\ShopfrontPos\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateShopfrontPos
{
       
    public function handle($request, Closure $next)
    {
        $tenant = $request->route('tenant')
            ?? $request->segment(1)
            ?? $request->input('tenant')
            ?? null;

        // Check tenant-specific guard first
        if (Auth::guard('shopfrontpos')->check()) {
            $user = Auth::guard('shopfrontpos')->user();
            if ($user->pos_type !== 'shopfrontpos') {
                Auth::guard('shopfrontpos')->logout();
                return redirect()->route('shopfrontpos.login', ['tenant' => $tenant])->with('error', 'Access denied.');
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

        return redirect()->route('shopfrontpos.login', ['tenant' => $tenant]);
    }


}
