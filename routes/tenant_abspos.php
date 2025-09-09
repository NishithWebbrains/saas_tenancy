<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Tenant\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Http\Controllers\POS\AbsPos\Auth\AuthenticatedSessionController;
use App\Http\Controllers\POS\AbsPos\Admin\PosUsersController;

// Auth routes (no middleware required)
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

            return view('layouts.tenant.abspos.dashboard', [
                'tenantDetails' => $tenantDetails,
                'products' => $products,
            ]);
        })->name('abspos.dashboard');
    Route::get('/posusers', [PosUsersController::class, 'view'])->name('abspos.posusers');
    Route::get('createuser', [PosUsersController::class, 'createuser'])->name('abspos.createuser');
    Route::post('/storeuser', [PosUsersController::class, 'storeuser'])->name('abspos.storeuser');
    Route::get('/storeusersdata', [PosUsersController::class, 'storeusersdata'])->name('abspos.storeusersdata');
});
