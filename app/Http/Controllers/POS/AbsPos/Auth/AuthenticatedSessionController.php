<?php
namespace App\Http\Controllers\POS\AbsPos\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        // Use ABS POS specific login view
        return view('abspos.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Make sure LoginRequest authenticates using abspos guard
        $request->authenticate('abspos'); // if you have a guard parameter

        $request->session()->regenerate();

        $user = $request->user('abspos');

        if ($user->hasRole('superadmin')) {
            return redirect()->route('abspos.admin.store-users.index');
        }

        if ($user->hasRole('storeadmin')) {
            return redirect()->route('abspos.stores.index');
        }
        if ($user->hasRole('staff')) {
            return redirect()->route('abspos.admin.dashboard');
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('abspos.admin.dashboard');
        }

        return redirect()->intended('/abspos'); // Default ABS POS dashboard
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('abspos')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/abspos/login');
    }
}
