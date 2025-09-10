<?php

use Illuminate\Support\Facades\Route;
use POS\SwiftPos\App\Models\Product;
use POS\SwiftPos\App\Models\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use POS\SwiftPos\App\Http\Controllers\Auth\AuthenticatedSessionController;
use POS\SwiftPos\App\Http\Controllers\Admin\PosUsersController;

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

        return view('swiftpos::layouts.dashboard', [
            'tenantDetails' => $tenantDetails,
            'products' => $products,
        ]);
        })->name('swiftpos.dashboard');

    Route::get('/posusers', [PosUsersController::class, 'view'])->name('swiftpos.posusers');
    Route::get('createuser', [PosUsersController::class, 'createuser'])->name('swiftpos.createuser');
    Route::post('/storeuser', [PosUsersController::class, 'storeuser'])->name('swiftpos.storeuser');
    Route::get('/storeusersdata', [PosUsersController::class, 'storeusersdata'])->name('swiftpos.storeusersdata');
});
