<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')
                ->where('guard_name', 'admin')
                ->whereNot('name', 'super_admin')],
            'avatar' => validationImage(),
        ];
    }

    protected function onCreate(): array
    {
        return array_merge($this->onData(), [
            'email' => ['required', 'email', 'string', 'min:2', 'max:100', Rule::unique('admins', 'email')],
            'phone' => ['required', 'string', Rule::unique('admins', 'phone')],
            'password' => ['required', 'string', 'min:2', 'max:100', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:2', 'max:100', 'same:password'],
        ]);
    }

    protected function onUpdate(): array
    {
        return array_merge($this->onData(), [
            'email' => ['required', 'email', 'string', 'min:2', 'max:255',
                Rule::unique('admins', 'email')
                    ->ignore(request()->segment(3), 'id')
            ],
            'phone' => ['required', 'string',
                Rule::unique('admins', 'phone')
                    ->ignore(request()->segment(3), 'id')
            ],
        ]);
    }

    protected function onChangePassword(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('admins', 'id')->whereNotIn('id', [1])],
            'password' => ['required', 'string', 'min:2', 'max:100', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:2', 'max:100', 'same:password'],
        ];
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.employee.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.employee.update')) {
            return $this->onUpdate();
        } elseif (request()->routeIs('admin.employee.change-password')) {
            return $this->onChangePassword();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return [
            'role_id' => _trans('Role name'),
        ];
    }
}
