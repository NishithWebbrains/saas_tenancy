<?php
// app/Http/Controllers/POS/AbsPos/Auth/AuthenticatedSessionController.php

namespace POS\SwiftPos\App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\POS\SwiftPosLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
       
        // $tenantId = request()->route('tenant')
        // ?? request()->segment(1)
        // ?? request()->input('tenant')
        // ?? null;
        //dd($tenantId);
        // return view('layouts.tenant.swiftpos.auth.login', compact('tenantId'));
        return view('layouts.tenant.swiftpos.auth.login');
    }

    public function store(SwiftPosLoginRequest $request): RedirectResponse
    {
        //dd('store method called');
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::guard('swiftpos')->user();
        
        // Redirect based on user roles
        // if ($user->hasRole('superadmin')) {
        //     return redirect()->route('swiftpos.admin.store-users.index');
        // }

        // if ($user->hasRole('storeadmin')) {
        //     return redirect()->route('swiftpos.stores.index');
        // }
        $tenantId = request()->route('tenant')
                ?? request()->segment(1)
                ?? request()->input('tenant')
                ?? null;
        if ($user->hasRole('staff')) {
            return redirect()->route('swiftpos.dashboard', ['tenant' => $tenantId]);
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('swiftpos.dashboard', ['tenant' => $tenantId]);
        }

        return redirect()->intended('/swiftpos/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $tenantId = request()->route('tenant')
        ?? request()->segment(1)
        ?? request()->input('tenant')
        ?? null;
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Auth::guard('swiftpos')->logout();
       
        return redirect()->route('swiftpos.login', ['tenant' => $tenantId]);
    }
}
