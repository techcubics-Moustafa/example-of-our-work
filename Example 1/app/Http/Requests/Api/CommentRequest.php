<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'real_estate_id' => ['required', 'integer', Rule::exists('real_estates', 'id')
                ->where('publish', Status::Active->value)
            ],
            'parent_id' => ['nullable', 'integer', Rule::exists('comments', 'id')],
            'comment' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()->all()));
    }

}
