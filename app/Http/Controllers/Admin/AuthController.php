<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthRequest;
use App\Interfaces\Auth\AuthRepositoryInterface;
use App\Mail\ResetPassword;
use App\Models\Admin;
use App\Traits\Helper\UploadFileTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepositoryInterface;
    use UploadFileTrait;

    public function __construct(AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->authRepositoryInterface = $authRepositoryInterface;
        $this->middleware(['guest:admin'])->only(['showLoginForm', 'login', 'resetPassword', 'postResetPassword', 'reset', 'postReset']);
        $this->middleware(['auth:admin'])->only(['logout', 'profile', 'storeProfile', 'changePassword']);
    }

    public function showLoginForm(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $routeLogin = route('admin.login');
        $resetPassword = route('admin.reset.password');
        return view('auth.login', compact('routeLogin', 'resetPassword'));
    }

    public function login(AuthRequest $request): \Illuminate\Http\RedirectResponse
    {
        if (!Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // if unsuccessful, then redirect back to the login with the form data
            return redirect()->back()->with('warning', _trans('Please Check your email and password is correct'))
                ->withInput($request->only('email', 'remember'));
        }
        // if successful, then redirect to their intended location
        if (!Auth::guard('admin')->user()->status == Status::Active->value) {
            Auth::guard('admin')->logout();
            return redirect()->back()->with('warning', _trans('Your account has been blocked'))
                ->withInput($request->only('email', 'remember'));
        }
        Auth::guard('web')->logout();
        return redirect()->intended(route('admin.dashboard'));

    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        $this->authRepositoryInterface->logout('admin');
        return redirect()->route('admin.login');
    }

    public function resetPassword(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $routeForgetPassword = route('admin.reset.password');
        $routeLogin = route('admin.login');
        return view('auth.passwords.email', compact('routeForgetPassword', 'routeLogin'));
    }

    public function postResetPassword(AuthRequest $request): \Illuminate\Http\RedirectResponse
    {
        $admin = Admin::query()->whereEmail($request->email)->first();
        $token = Str::random(70);
        DB::table('admin_password_resets')->updateOrInsert([
            'email' => $admin->email,
        ], [
            'email' => $admin->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        $admin['url'] = route('admin.reset', $token);
        try {
            Mail::to($admin->email)->send(new ResetPassword(['data' => $admin, 'token' => $token]));
            return redirect()->route('admin.login')->with('success', _trans('Done send reset link to account'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function reset($token): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $reset = DB::table('admin_password_resets')
            ->where([
                ['token', '=', $token],
                ['created_at', '>', Carbon::now()->subHours(2)],
            ])->first();
        if ($reset) {
            $routeReset = route('admin.reset', $token);
            return view('auth.passwords.reset', compact('routeReset', 'reset'));
        } else {
            return redirect()->route('admin.reset.password');
        }
    }

    public function postReset(AuthRequest $request, $token): \Illuminate\Http\RedirectResponse
    {
        $reset = DB::table('admin_password_resets')
            ->where([
                ['token', '=', $token],
                ['created_at', '>', Carbon::now()->subHours(2)],
            ])
            ->first();
        if ($reset) {
            Admin::query()->where('email', '=', $reset->email)->update([
                'email' => $reset->email,
                'password' => Hash::make($request->password)
            ]);
            DB::table('admin_password_resets')->where('email', '=', $request->email)->delete();
            return redirect()->route('admin.login')->with('success', _trans('Done reset password please login'));
        }
        return redirect()->route('admin.reset.password');
    }

    public function showFormProfile(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $admin = Admin::query()->findOrFail(Auth::guard('admin')->id());
        return view('admin.profile.profile', compact('admin'));
    }

    public function storeProfile(AuthRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $admin = Admin::query()->findOrFail(Auth::guard('admin')->id());
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->upload([
                'file' => 'avatar',
                'path' => 'admin',
                'upload_type' => 'single',
                'delete_file' => $admin->avatar ?? '',
            ]);
        }
        $admin->update($data);
        return redirect()->back()->with('success', _trans('Done Update profile Successfully'));

    }

    public function changePassword(AuthRequest $request): \Illuminate\Http\RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        if ($this->authRepositoryInterface->changePassword($request, $admin)) {
            return redirect()->back()->with('success', _trans('Password changed successfully'));
        }
        return redirect()->back()->withErrors([
            'old_password' => _trans('Please make sure your current password correct')
        ])->withInput($request->all());
    }
}
