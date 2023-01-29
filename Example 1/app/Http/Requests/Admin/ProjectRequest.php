<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProjectStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Helpers\CPU\Models;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'company_id' => ['required', 'integer',
                Rule::exists('companies', 'id')
                    ->where('user_id', request()->user_id)
                    ->where('status', Status::Active->value)
            ],
            'status' => ['required', 'string', Rule::enum(ProjectStatus::class)],
            'number_blocks' => ['required', 'integer', 'min:0'],
            'number_floors' => ['required', 'integer', 'min:0'],
            'number_flats' => ['required', 'integer', 'min:0'],
            'min_price' => ['required', 'numeric', 'min:0'],
            'max_price' => ['required', 'numeric', 'min:0', 'gte:min_price'],
            'open_sell_date' => ['required', 'date_format:Y-m-d'],
            'finish_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:open_sell_date'],
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

    protected function onUpdate(): array
    {
        $project = Project::query()->find(request()->segment(3));
        $project?->load('realEstate');
        $files = getFiles(['id' => request()->segment(3), 'type' => Project::class])->count();
        $rules = array_merge(RealEstateRequest::rules(), array_merge($this->onData(), Models::ruleImages($files, 5)));
        $rules = array_merge($rules, RealEstateRequest::validationDate($project->realEstate, $this->start_date));
        return array_merge(array_merge([
            'image' => validationImage()
        ], $rules), $this->onData());
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.project.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.project.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return array_merge(RealEstateRequest::attributes(), [
            'service_id' => _trans('service name'),
            'user_id' => _trans('user name'),
            'company_id' => _trans('company name'),
            'status' => _trans('project status'),
            'number_blocks' => _trans('number blocks'),
            'number_floors' => _trans('number floors'),
            'number_flats' => _trans('number flats'),
            'min_price' => _trans('min price'),
            'max_price' => _trans('max price'),
            'open_sell_date' => _trans('open sell date'),
            'finish date' => _trans('finish date'),
            'images' => _trans('images'),
            'images.' => _trans('images'),
            'image' => _trans('image'),
        ]);
    }

}
