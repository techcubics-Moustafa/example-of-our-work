<?php

namespace App\Http\Requests\Api;

use App\Enums\GenderType;
use App\Enums\Status;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')
                ->where('status', Status::Active->value)
            ],
            'governorate_id' => ['required', 'integer', Rule::exists('governorates', 'id')
                ->where('country_id', request()->country_id)
                ->where('status', Status::Active->value)
            ],
            'region_id' => ['required', 'integer', Rule::exists('regions', 'id')
                ->where('governorate_id', request()->governorate_id)
                ->where('status', Status::Active->value)
            ],
            'question' => ['required', 'string', 'min:1'],
        ];
    }

    protected function onCreate(): array
    {
        return $this->onData();
    }

    protected function onUpdate(): array
    {
        return $this->onData();
    }

    public function rules(): array
    {
        if (request()->routeIs('api.question.store')) {
            return $this->onData();
        } elseif (request()->routeIs('api.question.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()));
    }
}
