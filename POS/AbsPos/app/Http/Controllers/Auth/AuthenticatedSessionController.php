<?php
// app/Http/Controllers/POS/AbsPos/Auth/AuthenticatedSessionController.php

namespace POS\AbsPos\App\Http\Controllers\Auth;

use POS\AbsPos\App\Http\Controllers\Controller;
use POS\AbsPos\App\Http\Requests\AbsPosLoginRequest;
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
        return view('abspos::layouts.auth.login');
    }

    public function store(AbsPosLoginRequest $request): RedirectResponse
    {
        //dd('store method called');
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::guard('abspos')->user();
        
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
            return redirect()->route('abspos.dashboard', ['tenant' => $tenantId]);
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('abspos.dashboard', ['tenant' => $tenantId]);
        }

        return redirect()->intended('/abspos/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $tenantId = request()->route('tenant')
        ?? request()->segment(1)
        ?? request()->input('tenant')
        ?? null;
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Auth::guard('abspos')->logout();
       
        return redirect()->route('abspos.login', ['tenant' => $tenantId]);
    }
}
