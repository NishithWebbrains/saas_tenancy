<?php
// app/Http/Controllers/POS/AbsPos/Auth/AuthenticatedSessionController.php

namespace App\Http\Controllers\POS\ShopfrontPos\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\POS\ShopfrontPosLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('layouts.tenant.shopfrontpos.auth.login');
    }

    public function store(ShopfrontPosLoginRequest $request): RedirectResponse
    {
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

        if ($user->hasRole('staff')) {
            return redirect()->route('layouts.tenant.shopfrontpos.dashboard');
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('layouts.tenant.shopfrontpos.dashboard');
        }

        return redirect()->intended('/shopfrontpos/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('shopfrontpos')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/shopfrontpos/auth/login');
    }
}
