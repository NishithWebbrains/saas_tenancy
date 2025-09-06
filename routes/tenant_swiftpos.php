<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Tenant\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Http\Controllers\POS\SwiftPos\Auth\AuthenticatedSessionController;

// Auth routes (no middleware required)
Route::middleware([
    'web',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/swiftpos')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('swiftpos.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('swiftpos.login.submit');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('swiftpos.logout');
});


Route::middleware([
    'web',
    
    'auth.swiftpos',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/swiftpos')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::all();
        $tenantDetails = TenantDetail::all();

        return view('layouts.tenant.swiftpos.dashboard', [
            'tenantDetails' => $tenantDetails,
            'products' => $products,
        ]);
    })->name('swiftpos.dashboard');
});
