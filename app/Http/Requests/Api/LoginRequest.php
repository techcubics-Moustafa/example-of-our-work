<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class LoginRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:2', 'max:100'],
            'password' => ['required', 'string', 'min:2', 'max:100'],
            'remember_me' => ['sometimes', 'nullable'],
            'country_code' => ['nullable', 'string', Rule::exists('countries', 'code')
                ->where('status', Status::Active->value)
            ],
        ];
    }

    public function getCredentials(): array
    {
        // The form field for providing username or password
        // have name of "username", however, in order to support
        // logging users in with both (username and email)
        // we have to check if user has entered one or another
        $username = $this->get('username');

        if ($this->isEmail($username)) {
            return [
                'email' => $username,
                'password' => $this->get('password')
            ];
        }
        if ($this->isPhone($username)) {
            return [
                'phone' => (string)PhoneNumber::make($username, request('country_code')),
                'password' => $this->get('password')
            ];
        }


        return $this->only('username', 'password');
    }

    private function isEmail($param): bool
    {
        $factory = $this->container->make(ValidationFactory::class);

        return !$factory->make(
            ['username' => $param],
            ['username' => 'email']
        )->fails();
    }

    private function isPhone($param): bool
    {
        $factory = $this->container->make(ValidationFactory::class);

        return !$factory->make(
            ['username' => $param],
            ['username' => 'numeric',] // 'regex:/^(00966)[0-9]{9}$/'
        )->fails();
    }

    public function attributes(): array
    {
        return [
            'username' => _trans('Email Or Phone')
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()));
    }
}
