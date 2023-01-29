<?php

namespace App\Http\Requests\Api;

use App\Rules\LikeRule;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LikeRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'modelable_type' => ['required', 'string', 'in:real_estate,comment'],
            'modelable_id' => ['required', 'integer', new LikeRule($this->modelable_type)],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()));
    }
}
