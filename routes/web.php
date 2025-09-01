<?php
// routes/web.php (CENTRAL)

use App\Http\Controllers\Admin\StoreUserController;
use App\Http\Controllers\Admin\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\TenantUserController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

require __DIR__.'/auth.php';

// Override GET /login to force logout first
Route::get('/login', function (Request $request) {
    //dd("sdd");
    if (Auth::check()) {
        // Logout current user
        Auth::logout();

        // Invalidate session & regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return view('auth.login');
})->name('login');

// Default landing
Route::get('/', function () {
    //dd("sdd2");
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->hasRole('superadmin')) {
        return redirect()->route('admin.store-users.index');
    }

    if ($user->hasRole('storeadmin')) {
        return redirect()->route('stores.index');
    }

    // fallback if no role
    abort(403, 'Unauthorized.');
});

// Superadmin-only area
Route::middleware(['auth'])->group(function () {
    Route::get('admin/store-users/data', [StoreUserController::class, 'getData'])
        ->name('admin.store-users.data');
    Route::resource('admin/store-users', StoreUserController::class)->names([
        'index'   => 'admin.store-users.index',
        'store'   => 'admin.store-users.store',
        'create'  => 'admin.store-users.create',
        'edit'    => 'admin.store-users.edit',
        'update'  => 'admin.store-users.update',
        'destroy' => 'admin.store-users.destroy',
        'show'    => 'admin.store-users.show',
    ]);
});

// Storeadmin-only area
Route::middleware(['auth'])->group(function () {

    Route::get('stores/viewusers', [TenantUserController::class, 'viewusers'])->name('stores.viewusers');
    Route::get('stores/storeusersdata', [TenantUserController::class, 'storeusersdata'])->name('stores.storeusersdata');
    Route::get('stores/createuser', [TenantUserController::class, 'createuser'])->name('stores.createuser');
    Route::post('stores/storeuser', [TenantUserController::class, 'storeuser'])->name('stores.storeuser');
    Route::get('stores/data', [StoreController::class, 'getData'])->name('stores.data');
    Route::resource('stores', StoreController::class);
});

//tenant user management
//Route::middleware(['auth',])->group(function () {
