<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

Route::middleware([
    'web',
    'auth',
    'tenant.access',
    InitializeTenancyByPath::class,
])->prefix('/tenant/{tenant}/shopfrontpos')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::all();

        return view('layouts.admin.tenant.shopfrontpos.dashboard', [
            'tenantId' => tenant('id'),
            'products' => $products,
        ]);
    })->name('tenant.shopfrontpos.dashboard');
});
