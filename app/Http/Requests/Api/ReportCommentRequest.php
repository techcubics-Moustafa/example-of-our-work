<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ReportCommentRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment_id' => ['required', 'integer', Rule::exists('comments', 'id')],
            'report_comment_id' => ['required', 'integer', Rule::exists('report_comments', 'id')
                ->where('status', Status::Active->value)
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()));
    }
}
