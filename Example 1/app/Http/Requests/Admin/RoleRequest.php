<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onCreate(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255',
                Rule::unique('roles', 'name')->where('guard_name', 'admin')],
        ];
    }

    protected function onUpdate(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255',
                Rule::unique('roles', 'name')->whereNot('name', 'super_admin')
                    ->ignore(request()->segment(3), 'id')
            ],
        ];
    }

    protected function onDelete(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('roles', 'id')->where('guard_name', 'admin')],
        ];
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.role.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.role.update')) {
            return $this->onUpdate();
        } elseif (request()->routeIs('admin.role.destroy')) {
            return $this->onDelete();
        } else {
            return [];
        }
    }

    public function attributes()
    {
        return [
          'name' => _trans('Role name')
        ];
    }
}
