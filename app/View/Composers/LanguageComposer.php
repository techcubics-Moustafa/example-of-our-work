<?php

namespace App\View\Composers;

use Illuminate\View\View;

class LanguageComposer
{

    public function compose(View $view): void
    {
        $local = session()->has('local') ? session('local') : 'en';
        $view->with('languages', languages());
        $view->with('local', $local);
    }
}
