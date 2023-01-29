<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onLogin(): array
    {
        return [
            'email' => ['required', 'email', 'min:2', 'max:100', Rule::exists('admins', 'email')],
            'password' => ['required', 'string', 'min:2', 'max:100'],
            'remember_me' => ['sometimes', 'nullable']
        ];
    }

    protected function onForgotPassword(): array
    {
        return [
            'email' => ['required', 'email', 'min:2', 'max:100', Rule::exists('admins', 'email')],
        ];
    }

    protected function onResetPassword(): array
    {
        return [
            'email' => ['required', 'email', 'string', Rule::exists('admins', 'email')],
            'password' => ['required', 'confirmed', 'min:2', 'max:100'],
            'password_confirmation' => ['required', 'same:password', 'min:2', 'max:100'],
        ];
    }

    protected function onUpdateProfile(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'min:2', 'max:100',
                Rule::unique('admins', 'email')->ignore($this->user()->id, 'id')],
            'phone' => ['required', 'numeric',
                Rule::unique('admins', 'phone')->ignore($this->user()->id, 'id')],
            'avatar' => array_merge(['nullable',], validationImage()),
        ];
    }


    protected function onChangePassword(): array
    {
        return [
            'old_password' => ['required', 'string', 'min:2', 'max:100'],
            'password' => ['required', 'string', 'min:2', 'max:100', 'confirmed',],
            'password_confirmation' => ['required', 'string', 'same:password', 'min:2', 'max:100'],
        ];
        // 'regex:/^(?=[^a-z\n]*[a-z])(?=[^A-Z\n]*[A-Z])(?=[^\d\n]*\d)(?=[^!@?\n]*[!@?]).{8,}$/'
        /*
^(?=[^a-z\n]*[a-z]) # ensure one lower case letter
(?=[^A-Z\n]*[A-Z]) # ensure one upper case letter
(?=[^\d\n]*\d)     # ensure a digit
(?=[^!@?\n]*[!@?]) # special chars
.{10,}             # at least 10 characters long
$
  */
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.login')) {
            return $this->onLogin();
        } elseif (request()->routeIs('admin.reset.password')) {
            return $this->onForgotPassword();
        } elseif (request()->routeIs('admin.reset')) {
            return $this->onResetPassword();
        } elseif (request()->routeIs('admin.profile.account')) {
            return $this->onUpdateProfile();
        } elseif (request()->routeIs('admin.profile.security')) {
            return $this->onChangePassword();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return [
            'name' => _trans('Full name'),
            'old_password' => _trans('Current password'),
            'password' => _trans('New Password'),
            'password_confirmation' => _trans('Retype New Password'),
        ];
    }
}
