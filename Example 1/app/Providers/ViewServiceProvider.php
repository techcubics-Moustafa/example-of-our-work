<?php

namespace App\Providers;

use App\View\Composers\LanguageComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Using class based composers...
        View::composer([
            'admin.currency.form',
            'admin.country.form',
            'admin.governorate.form',
            'admin.region.form',
            'admin.category.form',
            'admin.special.form',
            'admin.feature.form',
            'admin.service.form',
            'admin.page.form',
            'admin.property.form',
            'admin.project.form',
            'admin.company.form',
            'admin.setting.index',
            'admin.report-comment.form',
        ], LanguageComposer::class);
    }
}
