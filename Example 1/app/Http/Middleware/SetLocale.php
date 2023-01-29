<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        session()->forget('direction');
        session()->forget('local');
        if (auth('admin')->check()) {
            $this->language(auth('admin')->user()->lang);
        } elseif (auth()->check()) {
            $this->language(auth()->user()->lang);
        } else {
            session()->put('direction', default_lang());
            session()->put('direction', 'rtl');
        }
        return $next($request);
    }

    protected function language($lang): void
    {
        $language = Language::query()->whereCode($lang)->first();
        if ($language) {
            session()->put('direction', $language->direction);
            session()->put('local', $language->code);
            App::setLocale($language->code);
        } else {
            session()->put('direction', 'rtl');
            session()->put('local', 'ar');
            App::setLocale('ar');
        }
    }
}
