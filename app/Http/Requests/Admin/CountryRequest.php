<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use App\Rules\CountryCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'icon' => validationImage(),
            'currency_id' => ['nullable', 'integer', Rule::exists('currencies', 'id')
                ->where('status', Status::Active->value)
            ],
        ];

    }

    protected function onCreate(): array
    {
        $rules = [
            'code' => ['required', 'string', 'min:1', 'max:10',
                Rule::unique('countries', 'code'),
                new CountryCode()
            ],
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('country_translations', 'name')],
                $locale . '.nationality' => ['sometimes', 'nullable', 'string', 'min:2', 'max:100'],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    protected function onUpdate(): array
    {
        $rules = [
            'code' => ['required', 'string', 'min:1', 'max:10',
                Rule::unique('countries', 'code')
                    ->ignore(request()->segment(3), 'id'),
                new CountryCode()
            ],
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100',
                    Rule::unique('country_translations', 'name')->ignore(request()->segment(3), 'country_id')],
                $locale . '.nationality' => ['sometimes', 'nullable', 'string', 'min:2', 'max:100'],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.country.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.country.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        $messages = [
            'icon' => _trans('icon'),
            'currency_id' => _trans('Currency name'),
            'code' => _trans('country code'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.name' => _trans('Country name') . ' (' . ucfirst($locale) . ')',
                $locale . '.nationality' => _trans('Nationality') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
