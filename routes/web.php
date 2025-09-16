<?php
// routes/web.php (CENTRAL)

use App\Http\Controllers\Admin\StoreUserController;
use App\Http\Controllers\Admin\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

require __DIR__.'/auth.php';

// Override GET /login to force logout first
Route::get('/login', function (Request $request) {
    
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
    Route::get('stores/data', [StoreController::class, 'getData'])->name('stores.data');
    Route::get('/stores/data/count', [StoreController::class, 'getDataCount'])->name('stores.data.count');
    Route::resource('stores', StoreController::class);
    
    Route::put('stores/{store}/zkong-update', [StoreController::class, 'updateZkongCredentials'])->name('stores.zkong-update');
    Route::put('stores/{store}/pos-vendor-update', [StoreController::class, 'updatePosVendorIdentifier'])->name('stores.pos-vendor-update');

});

//tenant user management
//Route::middleware(['auth',])->group(function () {
