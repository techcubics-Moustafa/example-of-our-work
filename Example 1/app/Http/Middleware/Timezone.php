<?php

namespace App\Http\Middleware;

use App\Helpers\Setting\Utility;
use Closure;

class Timezone
{

    public function handle($request, Closure $next)
    {
        $timezone = Utility::getValByName('timezone');
        if (!empty($timezone)) {
            date_default_timezone_set($timezone);
        } else {
            date_default_timezone_set('Asia/Riyadh');
        }
        return $next($request);

    }
}
