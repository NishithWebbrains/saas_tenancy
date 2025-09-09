<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Tenant\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Http\Controllers\POS\ShopfrontPos\Auth\AuthenticatedSessionController;
use App\Http\Controllers\POS\ShopfrontPos\Admin\PosUsersController;

// Auth routes (no middleware required)
Route::middleware([
    'web',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/shopfrontpos')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('shopfrontpos.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('shopfrontpos.login.submit');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('shopfrontpos.logout');
});

Route::middleware([
    'web',
    'auth.shopfrontpos',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/shopfrontpos')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::all();
        $tenantDetails = TenantDetail::all();

            return view('layouts.tenant.shopfrontpos.dashboard', [
                'tenantDetails' => $tenantDetails,
                'products' => $products,
            ]);
        })->name('shopfrontpos.dashboard');
    Route::get('/posusers', [PosUsersController::class, 'view'])->name('shopfrontpos.posusers');
    Route::get('createuser', [PosUsersController::class, 'createuser'])->name('shopfrontpos.createuser');
    Route::post('/storeuser', [PosUsersController::class, 'storeuser'])->name('shopfrontpos.storeuser');
    Route::get('/storeusersdata', [PosUsersController::class, 'storeusersdata'])->name('shopfrontpos.storeusersdata');
});
