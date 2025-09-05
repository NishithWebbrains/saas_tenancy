<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Tenant\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Http\Controllers\POS\AbsPos\Auth\AuthenticatedSessionController;

// Auth routes (no middleware required)
Route::prefix('/{tenant}/abspos')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('abspos.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('abspos.logout');
});

Route::middleware([
    'web',
    'auth',
    'tenant.access',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/abspos')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::all();
        $tenantDetails = TenantDetail::all();

        return view('layouts.tenant.abspos.dashboard', [
            'tenantDetails' => $tenantDetails,
            'products' => $products,
        ]);
    })->name('tenant.abspos.dashboard');
});
