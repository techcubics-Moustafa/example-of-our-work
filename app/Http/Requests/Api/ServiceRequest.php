<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Helpers\CPU\Models;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'integer',
                Rule::exists('services', 'id')
                    ->where('status', Status::Active->value)
            ],
            'first_name' => ['required', 'string', 'min:1', 'max:50'],
            'last_name' => ['required', 'string', 'min:1', 'max:50'],
            'country_code' => ['required', 'string', 'min:1', 'max:10',],
            'phone' => ['required', 'string', 'min:1', 'max:50',
                'phone:' . Models::country(request()->country_id)?->code,],

            'message' => ['required', 'string',],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()));
    }
}
