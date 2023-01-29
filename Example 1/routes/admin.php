<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\ReportCommentController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\OurPartnerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\SpecialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialMediaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:admin'])->controller(AuthController::class)->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login')->name('login');
    Route::get('rest/password', 'resetPassword')->name('reset.password');
    Route::post('rest/password', 'postResetPassword')->name('reset.password');
    Route::get('rest/password/{token}', 'reset')->name('reset');
    Route::post('rest/password/{token}', 'postReset')->name('reset');
});

Route::middleware(['auth:admin', 'check-status-admin'])->group(function () {
    Route::redirect('/admin', '/admin/dashboard');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/per', [DashboardController::class, 'permissionAdmin']);

    /* profile */ // not working
    Route::prefix('profile')->name('profile.')->controller(AuthController::class)->group(function () {
        Route::get('account', 'showFormProfile')->name('account');
        Route::post('account', 'storeProfile')->name('account');
        Route::post('security', 'changePassword')->name('security');
    });

    /* role */
    Route::resource('role', RoleController::class)->except(['show', 'destroy']);
    Route::post('role/destroy/{id}', [RoleController::class, 'destroy'])->name('role.destroy');


    /* staff alis employee super admin */
    Route::prefix('employee')->name('employee.')->controller(EmployeeController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
        Route::post('change-password', 'changePassword')->name('change-password');
    });
    Route::resource('employee', EmployeeController::class)->except(['show', 'destroy']);

    /* language */
    Route::prefix('language')->name('language.')->controller(LanguageController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
        Route::post('update-default', 'updateDefaultStatus')->name('update-default');
        Route::get('translate/{lang}', 'translate')->name('translate');
        Route::post('translate-submit/{lang}', 'translate_submit')->name('translate-submit');
    });
    Route::resource('language', LanguageController::class);

    /* setting */
    Route::prefix('setting')->name('setting.')->controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('email', 'saveEmailSettings')->name('email');
        Route::post('test-mail', 'testSendMail')->name('send-mail');
        Route::post('pusher', 'savePusherSettings')->name('pusher');
        Route::get('devices', 'devices')->name('devices');
        Route::get('activity-logs', 'activityLogs')->name('activity-logs');
    });

    /* pages */
    Route::resource('page', PageController::class)->except(['show', 'destroy']);
    Route::prefix('page')->name('page.')->controller(PageController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
        Route::get('destroy/{id}', 'destroy')->name('destroy');
    });

    /* currency */
    Route::resource('currency', CurrencyController::class)->except(['show']);
    Route::post('currency/update-status', [CurrencyController::class, 'updateStatus'])->name('currency.update-status');

    /* country */
    Route::resource('country', CountryController::class)->except(['show']);
    Route::post('country/update-status', [CountryController::class, 'updateStatus'])->name('country.update-status');

    /* governorate */
    Route::post('governorate/update-status', [GovernorateController::class, 'updateStatus'])->name('governorate.update-status');
    Route::resource('governorate', GovernorateController::class)->except(['show']);

    /* regions */
    Route::post('region/update-status', [RegionController::class, 'updateStatus'])->name('region.update-status');
    Route::resource('region', RegionController::class)->except(['show']);

    /* category */
    Route::prefix('category')->name('category.')->controller(CategoryController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
    });
    Route::resource('category', CategoryController::class)->except(['show', 'destroy']);

    /* feature */
    Route::prefix('feature')->name('feature.')->controller(FeatureController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
    });
    Route::resource('feature', FeatureController::class)->except(['show', 'destroy']);


    /* special */
    Route::prefix('special')->name('special.')->controller(SpecialController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
    });
    Route::resource('special', SpecialController::class)->except(['show', 'destroy']);

    /* service */
    Route::prefix('service')->name('service.')->controller(ServiceController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
    });
    Route::resource('service', ServiceController::class)->except(['show', 'destroy']);

    /* user */
    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
        Route::post('change-password', 'changePassword')->name('change-password');
    });
    Route::resource('user', UserController::class)->except(['destroy']);

    /* company */
    Route::prefix('company')->name('company.')->controller(CompanyController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
    });
    Route::resource('company', CompanyController::class)->except(['show', 'destroy']);

    /* property */
    Route::prefix('property')->name('property.')->controller(PropertyController::class)->group(function () {
        Route::post('update-publish', 'updatePublish')->name('update-publish');
        Route::post('delete-image', 'deleteImage')->name('delete-image');
    });
    Route::resource('property', PropertyController::class)->except('destroy');


    /* property */
    Route::prefix('project')->name('project.')->controller(ProjectController::class)->group(function () {
        Route::post('update-publish', 'updatePublish')->name('update-publish');
        Route::post('delete-image', 'deleteImage')->name('delete-image');
    });
    Route::resource('project', ProjectController::class)->except('destroy');


    /* social media */
    Route::prefix('social-media')->name('social-media.')->controller(SocialMediaController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
    });
    Route::resource('social-media', SocialMediaController::class);

    /* report-comment */
    Route::prefix('report-comment')->name('report-comment.')->controller(ReportCommentController::class)->group(function () {
        Route::post('update-status', 'updateStatus')->name('update-status');
    });
    Route::resource('report-comment', ReportCommentController::class);
});
