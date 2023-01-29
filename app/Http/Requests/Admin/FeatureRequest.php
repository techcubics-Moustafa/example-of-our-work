<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'ranking' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function onCreate(): array
    {
        $rules = [];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:1', 'max:50', Rule::unique('feature_translations', 'name')],
            ];
        }
        return array_merge($this->onData(),$rules);
    }

    protected function onUpdate(): array
    {
        $rules = [];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:1', 'max:50',
                    Rule::unique('feature_translations', 'name')
                        ->ignore(request()->segment(3), 'feature_id')
                ],
            ];
        }
        return array_merge($this->onData(),$rules);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.feature.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.feature.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        $messages = [
            'ranking' => _trans('Feature ranking'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.name' => _trans('Feature name') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
