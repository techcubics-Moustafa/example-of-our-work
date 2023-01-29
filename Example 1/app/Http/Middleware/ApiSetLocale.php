<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class ApiSetLocale
{

    public function handle($request, Closure $next)
    {
        if ($request->expectsJson()) {
            $request->merge(['lang' => request()->header('Accept-Language', default_lang())]);
            App::setLocale($request['lang']);
        }
        return $next($request);
    }
}
