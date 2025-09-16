<?php

use Illuminate\Support\Facades\Route;
use POS\AbsPos\App\Models\Product;
use POS\AbsPos\App\Models\TenantDetail;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use POS\AbsPos\App\Http\Controllers\Auth\AuthenticatedSessionController;
use POS\AbsPos\App\Http\Controllers\Admin\PosUsersController;
use POS\AbsPos\App\Http\Controllers\Admin\PosRolesController;
use POS\AbsPos\App\Http\Controllers\Admin\PermissionController;
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
    //roles
    Route::get('/roles', [PosRolesController::class, 'view'])->name('abspos.roles');
    Route::get('/createrole', [PosRolesController::class, 'createrole'])->name('abspos.createrole');
    Route::post('/addrole', [PosRolesController::class, 'addrole'])->name('abspos.addrole');
    Route::get('/roledata', [PosRolesController::class, 'roledata'])->name('abspos.roledata');

    //permission
    Route::get('/permission', [PermissionController::class, 'view'])->name('abspos.permission');
    Route::post('/{role}/savepermissions', [PermissionController::class, 'savepermissions'])->name('abspos.savepermissions');


    //tenant users
    Route::get('/posusers', [PosUsersController::class, 'view'])->name('abspos.posusers');
    Route::get('createuser', [PosUsersController::class, 'createuser'])->name('abspos.createuser');
    Route::post('/storeuser', [PosUsersController::class, 'storeuser'])->name('abspos.storeuser');
    Route::get('/storeusersdata', [PosUsersController::class, 'storeusersdata'])->name('abspos.storeusersdata');
});
