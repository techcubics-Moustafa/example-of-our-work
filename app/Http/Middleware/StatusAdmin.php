<?php

namespace App\Http\Middleware;

use App\Enums\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->status == Status::Active->value) {
            return $next($request);
        }
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
