<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        $rules = [
            'page_type' => ['required', 'string', 'min:2', 'max:255',
                'in:terms_condition,about_us,privacy_policy,footer,sharing_point'],
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:255',],
                $locale . '.description' => ['required', 'string', 'min:2',],
            ];
        }
        return $rules;
    }

    protected function onCreate(): array
    {
        return array_merge($this->onData(), [
            'image' => array_merge(['required'], validationImage()),
        ]);
    }

    protected function onUpdate(): array
    {
        return array_merge($this->onData(), [
            'id' => ['required', 'integer', Rule::exists('pages', 'id')],
            'image' => array_merge(['nullable'], validationImage())
        ]);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.page.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.page.update')) {
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
                $locale . '.name' => _trans('Page name') . ' (' . ucfirst($locale) . ')',
                $locale . '.description' => _trans('Page description') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
