<?php 
namespace App\Http\Middleware\POS;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateShopfrontPos
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('shopfrontpos')->check()) {
            return redirect()->route('shopfrontpos.login');
        }
        
         // Additional check to ensure user belongs to current tenant's ABS POS
         $user = Auth::guard('shopfrontpos')->user();
         if ($user->pos_type !== 'shopfrontpos') {
             Auth::guard('shopfrontpos')->logout();
             return redirect()->route('shopfrontpos.login')->with('error', 'Access denied.');
         }

        return $next($request);
    }
}
