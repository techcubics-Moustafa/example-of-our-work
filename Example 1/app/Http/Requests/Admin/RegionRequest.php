<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegionRequest extends FormRequest
{
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
                ->where('country_id', $this->country_id)
                ->where('status', Status::Active->value)
            ],
        ];
    }

    protected function onCreate(): array
    {
        $rules = [];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('region_translations', 'name')],
            ];
        }
        return array_merge($rules, $this->onData());
    }

    protected function onUpdate(): array
    {
        $rules = [];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100',
                    Rule::unique('region_translations', 'name')
                        ->ignore(request()->segment(3), 'region_id')
                ],
            ];
        }
        return array_merge($rules, $this->onData());
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.region.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.region.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        $messages = [
            'country_id' => _trans('Country name'),
            'governorate_id' => _trans('Governorate name'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.name' => _trans('Region name') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
