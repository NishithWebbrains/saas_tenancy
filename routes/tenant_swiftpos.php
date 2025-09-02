<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

Route::middleware([
    'web',
    'auth',
    'tenant.access',
    InitializeTenancyByPath::class,
])->prefix('/tenant/{tenant}/swiftpos')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::all();

        return view('layouts.admin.tenant.swiftpos.dashboard', [
            'tenantId' => tenant('id'),
            'products' => $products,
        ]);
    })->name('tenant.swiftpos.dashboard');
});
