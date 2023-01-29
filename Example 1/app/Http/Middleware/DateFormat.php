<?php

namespace App\Http\Middleware;

use App\Helpers\Setting\Utility;
use Closure;

class DateFormat
{

    public function handle($request, Closure $next)
    {
        $dateFormat = Utility::getValByName('date_format');
        if (!empty($dateFormat)) {
            config()->set('app.date_format', $dateFormat);
        }
        return $next($request);

    }
}
