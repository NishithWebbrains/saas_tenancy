<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Redirect superadmins to admin panel, others to home/dashboard
        // if ($request->user()->hasRole('superadmin')) {
        //     return redirect()->intended('/admin/stores-users');
        // }

        //return redirect()->intended('/dashboard');
    }
}
