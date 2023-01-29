<?php

namespace App\Http\Requests\Api;

use App\Enums\ProjectStatus;
use App\Enums\Status;
use App\Helpers\CPU\Models;
use App\Http\Requests\Admin\RealEstateRequest;
use App\Models\Project;
use App\Traits\Api\ApiResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function onData(): array
    {
        $user = auth('sanctum')->user();
        return [
            'company_id' => ['required', 'integer',
                Rule::exists('companies', 'id')
                    ->where('user_id', $user->id)
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

    public function onCreated(): array
    {
        $RealEstateRule = RealEstateRequest::rules();
        unset($RealEstateRule['publish'],$RealEstateRule['user_id']);
        $rules = array_merge($RealEstateRule, [
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => validationImage(true),
            'image' => validationImage(true),
            'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->format('Y-m-d')],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);
        return array_merge($this->onData(), $rules);
    }

    protected function onUpdated(): array
    {
        $RealEstateRule = RealEstateRequest::rules();
        unset($RealEstateRule['publish'],$RealEstateRule['user_id']);
        $project = Project::query()->find(request()->segment(3));
        $project?->load('realEstate');
        $files = getFiles(['id' => request()->segment(3), 'type' => Project::class])->count();
        $rules = array_merge($RealEstateRule, array_merge($this->onData(), Models::ruleImages($files, 5)));
        $rules = array_merge($rules, RealEstateRequest::validationDate($project->realEstate, $this->start_date));
        return array_merge(array_merge([
            'image' => validationImage()
        ], $rules), $this->onData());
    }

    public function rules(): array
    {
        if (request()->routeIs('api.project.store')) {
            return $this->onCreated();
        } elseif (request()->routeIs('api.project.update')) {
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
