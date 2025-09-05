<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureSuperadmin;




return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
            'superadmin' => EnsureSuperadmin::class, // ✅ register here
            'tenant.access' => \App\Http\Middleware\CheckTenantAccess::class,
            'tenant' => \App\Http\Middleware\TenantMiddleware::class,
            'auth.shopfrontpos' => \App\Http\Middleware\POS\AuthenticateShopfrontPos::class,
            'auth.swiftpos' => \App\Http\Middleware\POS\AuthenticateSwiftPos::class,
            'auth.abspos' => \App\Http\Middleware\POS\AuthenticateAbsPos::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
