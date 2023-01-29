<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Admin\RealEstateRequest;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\Admin\PropertyRequest as PropertyRequestAdmin;

class PropertyRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }


    public function onCreated(): array
    {
        $RealEstateRule = RealEstateRequest::rules();
        unset($RealEstateRule['publish'], $RealEstateRule['user_id']);
        $propertyRule = (new PropertyRequestAdmin())->onData();
        $rules = array_merge($RealEstateRule, [
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => validationImage(true),
            'image' => validationImage(true),
            'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->format('Y-m-d')],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);
        return array_merge($propertyRule, $rules);
    }

    protected function onUpdated(): array
    {
        $propertyRequestAdmin = new PropertyRequestAdmin();
        $RealEstateRule = RealEstateRequest::rules();
        unset($RealEstateRule['publish'], $RealEstateRule['user_id']);
        $propertyRule = $propertyRequestAdmin->onData();
        $rules = $propertyRequestAdmin->validationImages();
        return array_merge(array_merge([
            'image' => validationImage()
        ], $rules), $propertyRule);
    }

    public function rules(): array
    {
        if (request()->routeIs('api.property.store')) {
            return $this->onCreated();
        } elseif (request()->routeIs('api.property.update')) {
            return $this->onUpdated();
        } else {
            return [];
        }
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure(message: $validator->errors()->all()));
    }

}
