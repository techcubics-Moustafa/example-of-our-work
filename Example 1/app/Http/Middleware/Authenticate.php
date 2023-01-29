<?php

namespace App\Http\Middleware;

use App\Traits\Api\ApiResponses;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use ApiResponses;

    protected function redirectTo($request): \Illuminate\Http\JsonResponse|string|null
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }
        return $this->failure('Unauthenticated');
    }
}
