<?php

namespace App\Http\Controllers\User;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthRequest;
use App\Mail\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(['guest'])->only(['resetPassword', 'postResetPassword', 'reset', 'postReset']);
    }


    public function resetPassword(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $routeLogin = Utility::getValByName('link_website');
        $routeForgetPassword = route('user.reset.password');
        return view('auth.passwords.email', compact('routeLogin', 'routeForgetPassword'));
    }

    public function postResetPassword(AuthRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = User::query()->whereEmail($request->email)->first();
        if (!$user) {
            return redirect()->back()->with('warning', _trans('This account not available'));
        }
        $token = Str::random(70);
        DB::table('password_resets')->updateOrInsert([
            'email' => $user->email,
        ], [
            'email' => $user->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        $url = route('user.reset', $token);
        Mail::to($user->email)->send(new ResetPassword($user, $token, $url));
        DB::commit();
        return redirect()->back()
            ->with('success', _trans('Done Rest link is sent'))
            ->withInput($request->all());
    }

    public function getReset($token): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $reset = DB::table('password_resets')
            ->where('token', '=', $token)
            ->where('created_at', '>', now()->subHours(2))
            ->first();
        if (!empty($reset)) {
            $routeReset = route('user.reset', $token);
            return view('auth.passwords.reset', compact('reset', 'routeReset'));
        }
        return redirect()->route('user.get.reset.password')->with('warning', _trans('This link is expired'));
    }

    public function postReset(AuthRequest $request, $token): \Illuminate\Http\RedirectResponse
    {
        $data = DB::table('password_resets')->where('token', '=', $token)->first();
        $checkToken = DB::table('password_resets')
            ->where('token', '=', $token)
            ->where('created_at', '>', now()->subHours(2))
            ->first();
        if (empty($checkToken)) {
            return redirect()->route('user.get.reset.password')->with('warning', _trans('This link is expired'));
        }
        $user = User::query()->where([
            'email' => $checkToken->email,
        ])->first();
        if (!$user)
            return redirect()->back()->with('warning', _trans('This account not available'));
        $user->update([
            'email' => $checkToken->email,
            'password' => $request->password
        ]);
        DB::table('password_resets')
            ->where('email', '=', $request->email)
            ->delete();
        return redirect(Utility::getValByName('link_website'));
    }
}
