<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('storeadmin') || ! $user->hasRole('superadmin')) {
            abort(403, 'Unauthorized access .');
        }

        return $next($request);
    }
}
