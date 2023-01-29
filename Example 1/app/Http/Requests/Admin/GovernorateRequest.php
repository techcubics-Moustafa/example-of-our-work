<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GovernorateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')
                ->where('status', Status::Active->value)],
        ];
    }

    protected function onCreate(): array
    {
        $rules = [];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('governorate_translations', 'name')],
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
                    Rule::unique('governorate_translations', 'name')
                        ->ignore(request()->segment(3), 'governorate_id')
                ],
            ];
        }
        return array_merge($rules, $this->onData());
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.governorate.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.governorate.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        $messages = [
            'country_id' => _trans('Country name'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.name' => _trans('Governorate name') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
