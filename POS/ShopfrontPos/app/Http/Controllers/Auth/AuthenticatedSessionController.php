<?php
// app/Http/Controllers/POS/ShopfrontPos/Auth/AuthenticatedSessionController.php

namespace POS\ShopfrontPos\App\Http\Controllers\Auth;

use POS\ShopfrontPos\App\Http\Controllers\Controller;
use POS\ShopfrontPos\App\Http\Requests\ShopfrontPosLoginRequest;
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
        // return view('layouts.tenant.shopfrontpos.auth.login', compact('tenantId'));
        return view('shopfrontpos::layouts.auth.login');
    }

    public function store(ShopfrontPosLoginRequest $request): RedirectResponse
    {
        //dd('store method called');
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::guard('shopfrontpos')->user();
        
        // Redirect based on user roles
        // if ($user->hasRole('superadmin')) {
        //     return redirect()->route('shopfrontpos.admin.store-users.index');
        // }

        // if ($user->hasRole('storeadmin')) {
        //     return redirect()->route('shopfrontpos.stores.index');
        // }
        $tenantId = request()->route('tenant')
                ?? request()->segment(1)
                ?? request()->input('tenant')
                ?? null;
        if ($user->hasRole('Staff')) {
            return redirect()->route('shopfrontpos.dashboard', ['tenant' => $tenantId]);
        }

        if ($user->hasRole('Manager')) {
            return redirect()->route('shopfrontpos.dashboard', ['tenant' => $tenantId]);
        }
        if ($user->hasRole('Employee')) {
            return redirect()->route('shopfrontpos.dashboard', ['tenant' => $tenantId]);
        }

        return redirect()->intended('/shopfrontpos/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $tenantId = request()->route('tenant')
        ?? request()->segment(1)
        ?? request()->input('tenant')
        ?? null;
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Auth::guard('shopfrontpos')->logout();
       
        return redirect()->route('shopfrontpos.login', ['tenant' => $tenantId]);
    }
}
