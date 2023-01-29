<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ReportCommentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ShareController;
use Illuminate\Support\Facades\Route;

Route::controller(GeneralController::class)->name('general.')->group(function () {
    Route::get('languages', 'languages');
    Route::post('share-link', 'shareLink');
    Route::get('countries', 'countries')->name('countries');
    Route::get('governorates/{country}', 'governorates')->name('governorates');
    Route::get('regions/{country}/{governorate}', 'regions')->name('regions');
    Route::get('social-media', 'socialMedia');
    Route::get('setting', 'setting');
    Route::get('question/most-discussed', 'questions');
    Route::get('categories', 'categories');
    Route::get('specials', 'specials');
    Route::get('services', 'services');
    Route::get('features', 'features');
    Route::get('sub-categories/{categoryId}', 'subCategories');
    Route::get('real-estates', 'realEstates');
    Route::get('real-estate/{slug}', 'realEstate');
    Route::get('real-estates/{user}', 'realEstatesByUser');
    Route::get('properties', 'properties');
    Route::get('property/{id}', 'property');
    Route::get('projects', 'projects');
    Route::get('project/{id}', 'project');
    Route::get('comments/{real_estate_id}', 'comments');
    Route::get('companies', 'companies');
    Route::get('company/{slug}', 'company');
});

Route::controller(AuthController::class)->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::post('login/social', 'loginSocial')->name('login-social');
        Route::post('forget-password-web', 'forgetPasswordWeb')->name('forget-password-web');
        Route::post('forget-password', 'forgetPassword')->name('forget-password');
        Route::post('confirm-password', 'confirm')->name('confirm.forgot-password');
        Route::post('reset-password', 'resetPassword')->name('reset-password');
        Route::post('verification-code', 'sendVerificationCode')->name('verification-code');
    });
    Route::middleware(['auth:sanctum', 'user-status-blocked'])->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('update-profile', 'updateProfile')->name('update-profile');
        Route::post('change-language', 'changeLanguage')->name('change-language');
        Route::post('firebase-token', 'firebaseToken')->name('firebase-token');
        Route::post('update-password', 'updatePassword')->name('update-password');
        Route::post('remove-my-account', 'removeAccount')->name('remove-my-account');
        Route::get('logout', 'logout')->name('logout');

    });
});

Route::middleware(['auth:sanctum', 'user-status-blocked'])->group(function () {

    /* service */
    Route::post('service', ServiceController::class)->middleware(['country-id']);

    /* question */
    Route::apiResource('question', QuestionController::class)->except('destroy');

    /* like */
    Route::apiResource('like', LikeController::class)->only(['index', 'store']);

    /* share */
    Route::post('share', ShareController::class);

    /* comment */
    Route::apiResource('comment', CommentController::class)->only(['store', 'update']);

    /* project */
    Route::apiResource('project', ProjectController::class)->except(['destroy']);

    /* project */
    Route::apiResource('property', PropertyController::class)->except(['destroy']);
});

/* report-comment */
Route::apiResource('report-comment', ReportCommentController::class)->only(['index', 'store']);




