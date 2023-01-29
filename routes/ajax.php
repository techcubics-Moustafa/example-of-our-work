<?php

use App\Http\Controllers\Ajax\AjaxController;
use Illuminate\Support\Facades\Route;

Route::controller(AjaxController::class)->group(function () {
    Route::post('governorate-by-country', 'governorates')->name('governorate-by-country');
    Route::post('region-by-governorate', 'regions')->name('region-by-governorate');
    Route::post('role-by-owner', 'roles')->name('role-by-owner');
    Route::get('owners', 'owners')->name('owners');
    Route::post('sub-categories-by-category', 'subCategories')->name('sub-categories-by-category');
    Route::post('companies-by-user', 'companies')->name('companies-by-user');
});

