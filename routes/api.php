<?php 

use App\Http\Controllers\POSSyncController;

// Route::group(['prefix' => '{tenantPath}', 'middleware' => ['tenant']], function () {
//     Route::post('/pos-sync', [POSSyncController::class, 'sync']);
// });
Route::group(['prefix' => 'tenant/{tenantPath}', 'middleware' => ['tenant']], function () {
    Route::post('/pos-sync', [POSSyncController::class, 'sync']);
});
