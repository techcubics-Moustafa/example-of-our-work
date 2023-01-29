<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const OWNER_HOME = '/dashboard';
    public const ADMIN_HOME = '/admin/dashboard';


    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware(['json.response','api','api-locale'])
                ->name('api.')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web'])
                ->name('admin.')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web'])
                ->name('ajax.')
                ->prefix('ajax')
                ->group(base_path('routes/ajax.php'));
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
