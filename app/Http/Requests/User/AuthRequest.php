<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onForgotPassword(): array
    {
        return [
            'email' => ['required', 'email', 'min:2', 'max:100', Rule::exists('users', 'email')],
        ];
    }

    protected function onResetPassword(): array
    {
        return [
            'email' => ['required', 'email', 'string', Rule::exists('users', 'email')],
            'password' => ['required', 'confirmed', 'min:2', 'max:100'],
            'password_confirmation' => ['required', 'same:password', 'min:2', 'max:100'],
        ];
    }


    public function rules(): array
    {
        if (request()->routeIs('reset.password')) {
            return $this->onForgotPassword();
        } elseif (request()->routeIs('reset')) {
            return $this->onResetPassword();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return [
            'name' => _trans('Full name'),
            'password' => _trans('New password'),
        ];
    }
}
