<?php
// app/Http/Controllers/POS/AbsPos/Auth/AuthenticatedSessionController.php

namespace App\Http\Controllers\POS\AbsPos\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\POS\AbsPosLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('layouts.tenant.abspos.auth.login');
    }

    public function store(AbsPosLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::guard('abspos')->user();

        // Redirect based on user roles
        // if ($user->hasRole('superadmin')) {
        //     return redirect()->route('abspos.admin.store-users.index');
        // }

        // if ($user->hasRole('storeadmin')) {
        //     return redirect()->route('abspos.stores.index');
        // }

        if ($user->hasRole('staff')) {
            return redirect()->route('layouts.tenant.abspos.dashboard');
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('layouts.tenant.abspos.dashboard');
        }

        return redirect()->intended('/abspos/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('abspos')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/abspos/auth/login');
    }
}
