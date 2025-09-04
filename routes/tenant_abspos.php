<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Tenant\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

Route::middleware([
    'web',
    'auth',
    'tenant.access',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/abspos')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::all();
        $tenantDetails = TenantDetail::all();

        return view('layouts.admin.tenant.abspos.dashboard', [
            'tenantDetails' => $tenantDetails,
            'products' => $products,
        ]);
    })->name('tenant.abspos.dashboard');
});
