<?php

namespace App\Http\Controllers\Api;

use App\Events\UserLoginOrRegister;
use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Interfaces\Auth\AuthRepositoryInterface;
use App\Mail\ResetPassword;
use App\Models\FCMToken;
use App\Models\User;
use App\Models\VerificationCode;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Carbon\Carbon;
use donatj\UserAgent\UserAgentParser;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

class AuthController extends Controller
{
    use UploadFileTrait, ApiResponses;

    private AuthRepositoryInterface $authRepositoryInterface;

    public function __construct(AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->authRepositoryInterface = $authRepositoryInterface;
    }

    public function register(AuthRequest $request)
    {
        try {
            $data = $request->validated();
            $data['phone'] = (string)PhoneNumber::make($request->phone, $request->country_code);
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->upload([
                    'file' => 'avatar',
                    'path' => 'user',
                    'upload_type' => 'single',
                    'delete_file' => ''
                ]);
            }
            /* blocked */
            $user = User::query()
                ->where([
                    'blocked' => true,
                    'phone' => $data['phone'],
                ])
                ->first();

            DB::beginTransaction();
            if (!$user) {
                $user = User::query()->create($data);
                $user['token'] = $user->createToken($user->phone)->plainTextToken;
                event(new UserLoginOrRegister($user));
                $user->fresh(['country', 'governorate', 'region']);
                DB::commit();
                return $this->success(UserResource::make($user), message: _trans('Done register successfully'));
            }
            //$user->user->tokens()->delete();
            $user->update($data + ['blocked' => false]);
            $user->fresh(['country', 'governorate', 'region']);
            $user['token'] = $user->createToken($user->phone)->plainTextToken;
            event(new UserLoginOrRegister($user));
            DB::commit();
            return $this->success(UserResource::make($user), message: _trans('Done register successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->exception($exception);
        }
    }

    public function login(LoginRequest $request)
    {
        /* login from social */
        $credentials = $request->getCredentials();
        if ($request->country_code && Arr::exists($credentials, 'phone')) {
            $user = User::query()->wherePhone($credentials['phone'])->first();
            if (!$user) {
                return $this->failure(_trans('Email or Phone is not valid'));
            }
            /* is account blocked ==> remove account */
            if ($user->blocked) {
                $user->tokens()->delete();
                return $this->failure(_trans('Not found this account please register first'));
            }
            if (!$user->status) {
                $user->tokens()->delete();
                return $this->failure(_trans('This Account is blocked please call support'));
            }
            if (!Hash::check($request->password, $user->password)) {
                return $this->failure(_trans('Password is not correct'));
            }
        } else {
            if (!Auth::validate($credentials)) {
                return $this->failure(_trans('Email or Phone is not valid'));
            }
            $user = Auth::getProvider()->retrieveByCredentials($credentials);
        }

        if (!$user->status) {
            $this->authRepositoryInterface->logout('web');
            $user->tokens()->delete();
            return $this->failure(_trans('This Account is blocked please call support'));
        }
        /* is account blocked ==> remove account */
        if ($user->blocked) {
            $user->tokens()->delete();
            return $this->failure(_trans('Not found this account please register first'));
        }
        //$user->tokens()->delete();
        $user['token'] = $user->createToken($user->email)->plainTextToken;
        event(new UserLoginOrRegister($user));
        return $this->success(UserResource::make($user));
    }

    public function loginSocial(AuthRequest $request)
    {
        $data = $request->validated();
        $user = User::query()->with(['user'])
            ->where([
                'provider_type' => $request['provider_type'],
                'provider_id' => $request['provider_id'],
            ])
            ->first();
        if ($user) {
            $user->update([
                'blocked' => false,
                'provider_type' => $request->provider_type,
                'provider_id' => $request->provider_id,
            ]);
            $user->fresh(['country', 'governorate', 'region']);
            $user['token'] = $user->createToken($user->email)->plainTextToken;
            event(new UserLoginOrRegister($user));
            return $this->success(UserResource::make($user));
        }
        $splitName = explode(' ', $request->name, 2);
        $data['first_name'] = $splitName[0];
        $data['last_name'] = !empty($splitName[1]) ? $splitName[1] : '';
        $user = User::query()->updateOrCreate([
            'email' => $request->email,
        ], $data);
        $user['token'] = $user->createToken($user->email)->plainTextToken;
        event(new UserLoginOrRegister($user));
        return $this->success(UserResource::make($user));
    }

    public function forgetPasswordWeb(AuthRequest $request)
    {
        $user = User::query()->where(['email' => $request->email])->first();
        if (!$user) {
            return $this->failure(_trans('Not found this user'));
        }
        if ($user->blocked) {
            $user->tokens()->delete();
            return $this->failure(_trans('Not found this account please register first'));
        }
        if (!$user->status) {
            $user->tokens()->delete();
            return $this->failure(_trans('This Account is blocked please call support'));
        }
        $token = Str::random(70);
        try {
            DB::beginTransaction();
            $user->tokens()->delete();
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
            return $this->success(message: _trans('Done send rest link, please check your email address'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->exception($exception);
        }
    }

    public function forgetPassword(AuthRequest $request)
    {
        $validated = $request->validated();
        $code = random_int(100000, 999999);
        $message = __('auth.sms_message_confirmation', ['code' => $code]);
        if ($request->country_code) {
            $validated['username'] = (string)PhoneNumber::make($validated['username'], $request->country_code);
        }
        $user = User::findByEmailOrPhone($validated['username']);
        if (!$user) {
            return $this->failure(_trans('Not found this customer'));
        }
        if ($user->blocked) {
            $user->tokens()->delete();
            return $this->failure(_trans('Not found this account please register first'));
        }
        if (!$user->status) {
            $user->tokens()->delete();
            return $this->failure(_trans('This Account is blocked please call support'));
        }
        $verificationCode = new VerificationCode();
        $verificationCode->code = $code;

        DB::transaction(function () use ($user, $verificationCode) {
            $user->tokens()->delete();
            $user->verificationCode()->delete();
            $user->verificationCode()->save($verificationCode);
        });

        $fcmToken = $user->fcmTokens->pluck('fcm_token')->toArray();
        /* event(new SendNotificationUser([
             'notification_type' => 'forget_password',
             'code' => $code,
         ], $fcmToken, _trans('Done send code verification'), _trans('Done send code verification')));*/
        return $this->success(['code' => $code], message: $message);
    }

    public function confirm(AuthRequest $request): \Illuminate\Http\JsonResponse
    {
        //Check if verification code correct and mobile is exists
        $validated = $request->validated();
        if ($request->country_code) {
            $validated['username'] = (string)PhoneNumber::make($validated['username'], $request->country_code);
        }
        $user = User::findByEmailOrPhone($validated['username']);
        if (!$user) {
            return $this->failure(_trans('Not found this customer'));
        }
        $verificationCode = $user->verificationCode()->where('created_at', ">=", Carbon::now()->subMinutes(20))->first();

        if (!$verificationCode || $verificationCode['code'] != $request->code) {
            return $this->failure(_trans('The provided pin is incorrect.'));
        }
        $verificationCode->token = hash('sha256', $plainTextToken = Str::random(40));

        $user->verificationCode()->save($verificationCode);

        $token = $verificationCode->id . '|' . $plainTextToken;

        return $this->success([
            'token' => $token
        ]);

    }

    public function resetPassword(AuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $instance = VerificationCode::findToken($request->token);
        if (!$instance) {
            return $this->failure("Unauthorized.");
        }
        $user = $instance->modelable;
        DB::transaction(function () use ($user, $instance, $request) {
            $user->update(['password' => $request->password]);
            $user->tokens()->delete();
            $instance->delete();
        });
        return $this->success(message: _trans("Password changed successfully"));
    }

    public function profile()
    {
        $user = auth('sanctum')->user()->load(['country', 'governorate', 'region']);
        return $this->success(UserResource::make($user));
    }

    public function firebaseToken(AuthRequest $request)
    {
        $user = auth('sanctum');
        $parser = new UserAgentParser();
        $ua = $parser->parse();
        $platform = $ua->platform();
        $browser = $ua->browser();
        $browserVersion = $ua->browserVersion();

        FCMToken::query()
            ->updateOrCreate([
                'tokenable_type' => User::class,
                'tokenable_id' => $user->id(),
                'fcm_token' => $request->fcm_token
            ], [
                'tokenable_type' => User::class,
                'tokenable_id' => $user->id(),
                'fcm_token' => $request->fcm_token,
                'device_name' => $browser,
                'lang' => $request->header('Accept-Language', locale()),
            ]);
        return $this->success(message: _trans('Done save token successfully'));
    }

    public function updateProfile(AuthRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['phone'] = (string)PhoneNumber::make($request->phone, $request->country_code);
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->upload([
                'file' => 'avatar',
                'path' => 'user',
                'upload_type' => 'single',
                'delete_file' => $user->avatar ?? ''
            ]);
        }
        $user->update($data);
        $user->fresh(['country', 'governorate', 'region']);
        return $this->success(UserResource::make($user), message: _trans('Done updated profile successfully'));
    }

    public function updatePassword(AuthRequest $request)
    {
        try {
            $user = auth()->user();
            if ($this->authRepositoryInterface->changePassword($request, $user)) {
                return $this->success(message: _trans('Password changed successfully'));
            }
            return $this->failure(_trans('Please make sure your old password correct'));

        } catch (\Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authRepositoryInterface->logout('web');
        $request->user()->currentAccessToken()->delete();
        FCMToken::query()->where([
            'tokenable_type' => User::class,
            'tokenable_id' => $request->user()->id,
        ])->delete();
        return $this->success(message: _trans('Logged out successfully.'));
    }

    public function changeLanguage(AuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->update(['lang' => $request->code]);
        return $this->success(message: _trans('Done Update language'));
    }

    public function removeAccount(AuthRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $user->tokens()->delete();
            $user->currentAccessToken()->delete();
            $user->blockers()->create(['note' => $request->note]);
            $user->update(['blocked' => true, 'email' => null]);
            DB::commit();
            return $this->success(message: _trans('Done remove account successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->failure($exception->getMessage());
        }

    }
}
