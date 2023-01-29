<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('download-app', function () {
    return view('download-app');
})->name('download-app');

Route::get('lang/{locale}/{guard?}', [HomeController::class, 'language'])->name('lang');
Route::get('share-url', [HomeController::class, 'getSharedLinkPreview'])->name('link-preview');

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('rest/password', [AuthController::class, 'resetPassword'])->name('get.reset.password');
        Route::post('rest/password', [AuthController::class, 'postResetPassword'])->name('reset.password');
        Route::get('rest/password/{token}', [AuthController::class, 'getReset'])->name('reset');
        Route::post('rest/password/{token}', [AuthController::class, 'postReset'])->name('reset');
    });
});
