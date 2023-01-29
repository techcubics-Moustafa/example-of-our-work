<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use App\Enums\UserType;
use App\Helpers\CPU\Models;
use App\Models\User;
use App\Rules\FormatPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:1', 'max:50'],
            'last_name' => ['required', 'string', 'min:1', 'max:50'],
            'user_type' => ['required', 'string', 'min:1', 'max:50', Rule::enum(UserType::class)],
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')
                ->where('status', Status::Active->value)
            ],
            'governorate_id' => ['required', 'integer', Rule::exists('governorates', 'id')
                ->where('country_id', $this->country_id)
                ->where('status', Status::Active->value)
            ],
            'region_id' => ['required', 'integer', Rule::exists('regions', 'id')
                ->where('governorate_id', $this->governorate_id)
                ->where('status', Status::Active->value)
            ],
            'address' => ['nullable', 'string', 'min:2', 'max:255'],
            'avatar' => validationImage(),
        ];
    }

    protected function onCreate(): array
    {
        return array_merge($this->onData(), [
            'email' => ['required', 'email', 'string', 'min:2', 'max:255',
                Rule::unique('users', 'email')
            ],
            'phone' => ['required', 'string',
                Rule::unique('users', 'phone'),
                'phone:' . Models::country($this->country_id)?->code,
                new FormatPhone(Models::country($this->country_id)?->code, User::query())
            ],
            'password' => ['required', 'string', 'min:2', 'max:100', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:2', 'max:100', 'same:password'],
        ]);
    }

    protected function onUpdate(): array
    {
        $id = request()->segment(3);
        return array_merge($this->onData(), [
            'email' => ['required', 'email', 'string', 'min:2', 'max:255',
                Rule::unique('users', 'email')
                    ->ignore($id, 'userable_id')
            ],
            'phone' => ['required', 'string',
                Rule::unique('users', 'phone')->ignore($id, 'id'),
                'phone:' . Models::country($this->country_id)?->code,
                new FormatPhone(Models::country($this->country_id)?->code, User::query(), $id)
            ],
        ]);
    }

    protected function onChangePassword(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('users', 'id')],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],

        ];
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.user.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.user.update')) {
            return $this->onUpdate();
        } elseif (request()->routeIs('admin.user.change-password')) {
            return $this->onChangePassword();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return [
            'first_name' => _trans('first name'),
            'last_name' => _trans('last name'),
            'address' => _trans('address'),
            'avatar' => _trans('avatar'),
            'email' => _trans('email'),
            'phone' => _trans('phone'),
            'password' => _trans('password'),
            'password_confirmation' => _trans('password confirmation'),
            'country_id' => _trans('Country name'),
            'governorate_id' => _trans('Governorate name'),
            'region_id' => _trans('Region name'),
        ];
    }
}
