<?php

namespace App\Http\Requests\Api;

use App\Enums\ProviderType;
use App\Enums\Status;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ShareRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider_type' => ['required', 'string', Rule::enum(ProviderType::class)],
            'real_estate_id' => ['required', 'integer', Rule::exists('real_estates', 'id')
                ->where('publish', Status::Active->value)
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()));
    }
}
