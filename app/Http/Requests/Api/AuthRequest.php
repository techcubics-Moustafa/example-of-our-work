<?php

namespace App\Http\Requests\Api;

use App\Enums\GenderType;
use App\Enums\Status;
use App\Enums\UserType;
use App\Helpers\CPU\Models;
use App\Models\Customer;
use App\Models\User;
use App\Rules\FormatPhone;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AuthRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    protected function onUser(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:1', 'max:50'],
            'last_name' => ['required', 'string', 'min:1', 'max:50'],
            'user_type' => ['required', 'string', 'min:1', 'max:50', Rule::enum(UserType::class)],
            'avatar' => validationImage(),
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')
                ->where('status', Status::Active->value)
            ],
            'country_code' => ['required', 'string', Rule::exists('countries', 'code')
                ->where('id', $this->country_id)
                ->where('status', Status::Active->value)
            ],
            'governorate_id' => ['nullable', 'integer', Rule::exists('governorates', 'id')
                ->where('country_id', $this->country_id)
                ->where('status', Status::Active->value)
            ],
            'region_id' => ['nullable', 'integer', Rule::exists('regions', 'id')
                ->where('governorate_id', $this->governorate_id)
                ->where('status', Status::Active->value)
            ],
            'address' => ['required', 'string', 'min:2', 'max:255'],
            'gender' => ['nullable', 'string', Rule::enum(GenderType::class)],
        ];
    }

    protected function onRegister(): array
    {
        return array_merge($this->onUser(), [
            'email' => ['required', 'email', 'min:2', 'max:100', Rule::unique('users', 'email')],
            'phone' => ['required', 'string',
                Rule::unique('users', 'phone')
                    ->where('blocked', false),
                'phone:' . Models::country($this->country_id)?->code,
                new FormatPhone(Models::country($this->country_id)?->code, User::query()->where('blocked', false))
            ],
            'password' => ['required', 'string', 'min:2', 'max:100', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:2', 'max:100', 'same:password'],
        ]);
    }

    protected function onLoginSocial(): array
    {
        return [
            'provider_type' => ['required', 'string', 'min:2', 'max:100', 'in:facebook,google'],
            'provider_id' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'min:2', 'max:100'],
            'name' => ['required', 'string', 'min:1', 'max:100'],
        ];
    }

    protected function onForgotPasswordWeb(): array
    {
        return [
            'email' => ['required', 'min:1', 'max:100', Rule::exists('users', 'email')],
        ];
    }

    protected function onForgotPassword(): array
    {
        return [
            'username' => ['required', 'min:2', 'max:100',],
            'country_code' => ['nullable', 'string', Rule::exists('countries', 'code')
                ->where('status', Status::Active->value)],
        ];
    }

    protected function onConfirm(): array
    {
        return [
            'username' => ['required', 'min:2', 'max:100',],
            'code' => ['required', 'string', 'min:2', 'max:100'],
            'country_code' => ['nullable', 'string', Rule::exists('countries', 'code')
                ->where('status', Status::Active->value)],
        ];
    }

    protected function onResetPassword(): array
    {
        return [
            'token' => ['required', 'string', 'min:2', 'max:100'],
            'password' => ['required', 'string', 'min:2', 'max:100', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:2', 'max:100', 'same:password'],
        ];
    }

    protected function onUpdateProfile(): array
    {
        return array_merge($this->onUser(), [
            'email' => ['required', 'email', 'min:2', 'max:100',
                Rule::unique('users', 'email')
                    ->ignore(auth()->id(), 'id')
            ],
            'phone' => ['required', 'string',
                Rule::unique('users', 'phone')
                    ->ignore(auth()->id(), 'id'),
                'phone:' . Models::country($this->country_id)?->code,
                new FormatPhone(Models::country($this->country_id)?->code, User::query(), auth()->id())
            ],
        ]);

    }

    protected function onChangePassword(): array
    {
        return [
            'old_password' => 'required|min:2|max:100',
            'password' => ['required', 'string', 'min:2', 'max:100', 'confirmed'],
            'password_confirmation' => ['required', 'same:password', 'min:2', 'max:100'],
        ];
    }

    protected function onFirebaseToken(): array
    {
        return [
            'fcm_token' => ['required', 'string', 'min:2', 'max:255',],
        ];
    }

    protected function onChangeLanguage(): array
    {
        return [
            'code' => ['required', 'string', 'min:2', 'max:255',
                Rule::exists('languages', 'code')
                    ->where('status', Status::Active->value)
            ],
        ];
    }

    protected function onRemoveAccount(): array
    {
        return [
            'note' => ['required', 'string', 'min:2', 'max:255'],
        ];
    }

    public function rules(): array
    {
        if (request()->routeIs('api.register')) {
            return $this->onRegister();
        } elseif (request()->routeIs('api.forget-password-web')) {
            return $this->onForgotPasswordWeb();
        } elseif (request()->routeIs('api.forget-password')) {
            return $this->onForgotPassword();
        } elseif (request()->routeIs('api.confirm.forgot-password')) {
            return $this->onConfirm();
        } elseif (request()->routeIs('api.reset-password')) {
            return $this->onResetPassword();
        } elseif (request()->routeIs('api.update-profile')) {
            return $this->onUpdateProfile();
        } elseif (request()->routeIs('api.change-password')) {
            return $this->onChangePassword();
        } elseif (request()->routeIs('api.remove-my-account')) {
            return $this->onRemoveAccount();
        } elseif (request()->routeIs('api.firebase-token')) {
            return $this->onFirebaseToken();
        } elseif (request()->routeIs('api.login-social')) {
            return $this->onLoginSocial();
        } elseif (request()->routeIs('api.change-language')) {
            return $this->onChangeLanguage();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return [
            'first_name' => _trans('first name'),
            'last_name' => _trans('last name'),
            'country_id' => _trans('Country name'),
            'governorate_id' => _trans('Governorate name'),
            'region_id' => _trans('Region name'),
            'avatar' => _trans('avatar'),
            'gender' => _trans('gender'),
            'email' => _trans('email'),
            'phone' => _trans('phone'),
            'password' => _trans('password'),
            'password_confirmation' => _trans('password confirmation'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()));
    }
}
