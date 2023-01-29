<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onCreate(): array
    {
        $rules = [
            'code' => ['required', 'string', 'min:1', 'max:255', Rule::unique('currencies', 'code')],

        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('currency_translations', 'name')],
            ];
        }
        return $rules;
    }

    protected function onUpdate(): array
    {
        $rules = [
            'id' => ['required', 'integer', Rule::exists('currencies', 'id')],
            'code' => ['required', 'string', 'min:1', 'max:255',
                Rule::unique('currencies', 'code')->ignore($this->id, 'id')
            ],

        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100',
                    Rule::unique('currency_translations', 'name')->ignore($this->id, 'currency_id')],
            ];
        }
        return $rules;
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.currency.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.currency.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes()
    {
        $messages = [];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.name' => _trans('Currency name') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
