<?php

namespace App\Http\Requests\Admin;

use App\Enums\ModerationStatus;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Enums\Status;
use App\Helpers\CPU\Models;
use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function onData(): array
    {
        return [
            'type' => ['required', 'string', Rule::enum(PropertyType::class)],
            'status' => ['required', 'string', Rule::enum(PropertyStatus::class)],
            'moderation_status' => ['required', 'string', Rule::enum(ModerationStatus::class)],
            'project_id' => ['sometimes', 'nullable', 'integer',
                Rule::exists('projects', 'id')
            ],
            'number_bedrooms' => ['required', 'integer', 'min:0'],
            'number_bathrooms' => ['required', 'integer', 'min:0'],
            'number_floors' => ['required', 'integer', 'min:0'],
            'square' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function onCreate(): array
    {
        $rules = array_merge(RealEstateRequest::rules(), [
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => validationImage(true),
            'image' => validationImage(true),
            'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->format('Y-m-d')],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);
        return array_merge($this->onData(), $rules);
    }

    public function validationImages(): array
    {
        $property = Property::query()->find(request()->segment(3));
        $property?->load('realEstate');
        $files = getFiles(['id' => request()->segment(3), 'type' => Property::class])->count();
        $rules = array_merge((new RealEstateRequest())->rules(), array_merge($this->onData(), Models::ruleImages($files, 5)));
        return array_merge($rules, RealEstateRequest::validationDate($property->realEstate, $this->start_date));
    }

    protected function onUpdate(): array
    {
        $rules = $this->validationImages();
        return array_merge(array_merge([
            'image' => validationImage()
        ], $rules), $this->onData());
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.property.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.property.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return array_merge(RealEstateRequest::attributes(), [
            'user_id' => _trans('user name'),
            'type' => _trans('property type'),
            'status' => _trans('property status'),
            'moderation_status' => _trans('moderation status'),
            'project_id' => _trans('project name'),
            'number_bedrooms' => _trans('number bedrooms'),
            'number_bathrooms' => _trans('number bathrooms'),
            'number_floors' => _trans('number floors'),
            'square' => _trans('square'),
            'price' => _trans('price'),
            'images' => _trans('images'),
            'images.' => _trans('images'),
            'image' => _trans('image'),
        ]);
    }
}
