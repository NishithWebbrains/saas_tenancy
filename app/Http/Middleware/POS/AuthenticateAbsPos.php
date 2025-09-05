<?php 
namespace App\Http\Middleware\POS;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAbsPos
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('abspos')->check()) {
            return redirect()->route('abspos.login');
        }
        
         // Additional check to ensure user belongs to current tenant's ABS POS
         $user = Auth::guard('abspos')->user();
         if ($user->pos_type !== 'abspos') {
             Auth::guard('abspos')->logout();
             return redirect()->route('abspos.login')->with('error', 'Access denied.');
         }

        return $next($request);
    }
}
