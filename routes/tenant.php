<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Models\Product;

use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/


Route::middleware([
    'web',
    'auth',
    'tenant.access',
    InitializeTenancyByPath::class,
])->prefix('/tenant/{tenant}')->group(function () {
    Route::get('/dashboard2', function () {
        $products = Product::all();

        return view('layouts.admin.tenant.dashboard', [
            'tenantId' => tenant('id'),
            'products' => $products
        ]);
    })->name('tenant.dashboard');
});