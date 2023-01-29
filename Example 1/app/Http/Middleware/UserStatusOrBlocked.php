<?php

namespace App\Http\Middleware;

use App\Enums\Status;
use App\Traits\Api\ApiResponses;
use Closure;

class UserStatusOrBlocked
{
    use ApiResponses;

    public function handle($request, Closure $next)
    {
        if (!auth('sanctum')->user()->status) {
            return $this->failure(_trans('Your account is not active please call support'));
        }
        if (auth('sanctum')->user()->blocked) {
            return $this->failure(_trans('Not found this account places registered'));
        }
        return $next($request);

    }
}
