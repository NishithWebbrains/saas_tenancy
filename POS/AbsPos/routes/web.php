<?php

use Illuminate\Support\Facades\Route;
use POS\AbsPos\App\Models\Product;
use POS\AbsPos\App\Models\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use POS\AbsPos\App\Http\Controllers\Auth\AuthenticatedSessionController;
use POS\AbsPos\App\Http\Controllers\Admin\PosUsersController;

Route::middleware([
    'web',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/abspos')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('abspos.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('abspos.login.submit');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('abspos.logout');
});

Route::middleware([
    'web',
    'auth.abspos',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}/abspos')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::all();
        $tenantDetails = TenantDetail::all();

        return view('abspos::layouts.dashboard', [
            'tenantDetails' => $tenantDetails,
            'products' => $products,
        ]);
        })->name('abspos.dashboard');

    Route::get('/posusers', [PosUsersController::class, 'view'])->name('abspos.posusers');
    Route::get('createuser', [PosUsersController::class, 'createuser'])->name('abspos.createuser');
    Route::post('/storeuser', [PosUsersController::class, 'storeuser'])->name('abspos.storeuser');
    Route::get('/storeusersdata', [PosUsersController::class, 'storeusersdata'])->name('abspos.storeusersdata');
});
