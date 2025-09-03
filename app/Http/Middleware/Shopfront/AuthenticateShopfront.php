<?php 
namespace App\Http\Middleware\Shopfront;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateShopfront
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('shopfrontpos')->check()) {
            return redirect()->route('shopfrontpos.login');
        }

        return $next($request);
    }
}
