<?php


namespace App\Repositories\Auth;


use App\Interfaces\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthRepository implements AuthRepositoryInterface
{

    public function changePassword($request, $user): bool
    {
        if (Hash::check($request->old_password, $user->password)) {
            if ($user->update(['password' => $request->password])) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function logout($guard)
    {
        $guard = !empty($guard) ? $guard : config('auth.defaults.guard');
        Session::flush();
        Auth::guard($guard)->logout();
    }

}
